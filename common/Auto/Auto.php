<?php

/**
 * Скрипт загрузки телефонных номеров из ФТП выполняется автоматически
 * User: griff19
 * Date: 10.07.2016
 * Time: 16:42
 * @property  string ip_server
 * @property  string username
 * @property  string pass
 */

class FtpWork
{
    private static $ip_server;
    private static $username;
    private static $pass;

    function __construct(){
        $params = require(__DIR__ . '/../config/params-local.php');

        $this->ip_server = $params['ftp']['server'];
        $this->username = $params['ftp']['user'];
        $this->pass = $params['ftp']['pass'];
    }
    /**
     * Скачиваем файл с сервера
     * @param string $server_file
     * @param string $local_file
     * @return bool
     */
    public function download($server_file, $local_file){

        $conn_id = ftp_connect($this->ip_server);
        $login_result = ftp_login($conn_id, $this->username, $this->pass);
        //Logs::add('Результат аторизации на ftp: ' . $login_result);
        ftp_pasv($conn_id, true);

        $dir = dirname($server_file); //Получаем имя каталога
        $filename = basename($server_file); //Получаем имя файла
        if (!empty($dir)){
            ftp_chdir($conn_id, $dir); //если в имени файла указан каталог то выбираем его
        }

        if (ftp_get($conn_id, $local_file, $filename, FTP_BINARY)){
            ftp_close($conn_id);
            //Logs::add('Файл скачан ' . $server_file . ' как ' . $local_file);
            return true;
        } else {
            ftp_close($conn_id);
            return false;
        }
    }
}

/**
 * @property string dsn
 * @property string user
 * @property string pass
 * @property string table
 * @property PDO db
 */
class Main
{
    private static $dsn;
    private static $user;
    private static $pass;
    private static $db;
    private static $table;

    function __construct(){
        $params = require(__DIR__ . '/../config/main-local.php');
        $this->dsn = $params['components']['db']['dsn'];
        $this->user = $params['components']['db']['username'];
        $this->pass = $params['components']['db']['password'];
        $this->table = "cell_numbers";
    }

    /**
     * Скачиваем файлы с ФТП
     * @return bool
     */
    public function DwnFtp()
    {
        $fileloc = __DIR__ . '/../../backend/web/in/cellnumbers.txt';
        $fileftp = 'itbase/MobilNum.txt';

        $ftp = new FtpWork();
        if ($ftp->download($fileftp, $fileloc)) {
            echo "download file Cellnumbers.txt\n\r";
        } else {
            echo "file isnt downloaded\n\r";
        }

        $fileloc = __DIR__ . '/../../backend/web/in/employees.txt';
        $fileftp = 'itbase/employees.txt';

        if ($ftp->download($fileftp, $fileloc)) {
            echo "download file Employees.txt\n\r";
        } else {
            echo "file isnt downloaded\n\r";
        }

        //self::ReadFileCells();
        //self::ReadfileEmployees();

        return true;
    }

    /**
     * @return PDO
     */
    public function Connect(){
        $this->db = new PDO($this->dsn, $this->user, $this->pass);
        return $this->db;
    }

    /**
     * Обрабатываем загруженный файл с телефонами
     */
    public function ReadFileCells()
    {
        echo 'read file'. "\n\r";
        $filename = __DIR__ . '/../../backend/web/in/cellnumbers.txt';

        $readfile = fopen($filename, 'r');
        $db = $this->db;
        //$db = new PDO($this->dsn, $this->user, $this->pass);
        //инвертируем статус. номера, не прошедшие обработку, останутся с отрицательным статусом
        $db->exec("UPDATE ".$this->table." SET status = status*(-1)");

        $cells = $db->prepare("SELECT employee_id, status FROM ".$this->table." WHERE cell_number = :cell_number");
        //запрос получения идентификатора пользователя по УИД
        $employees = $db->prepare("SELECT id FROM employees WHERE unique_1c_number = :unique_1c_number");
        //запрос наличия телефонных номеров у сотрудника
        $count_pre = $db->prepare("SELECT COUNT(*) FROM ".$this->table." WHERE employee_id = :employee_id ");
        //запрос на обновление пользователя телефонного номера
        $update_cells = $db->prepare("UPDATE ".$this->table." SET employee_id = :emp_id, status = :status WHERE cell_number = :cell");
        //запрос на обновление статуса телефонного номера
        $update_cells_status = $db->prepare("UPDATE ".$this->table." SET status = :status WHERE cell_number = :cell");
        //добавляем новый номер телефона
        $insert_cells = $db->prepare("INSERT INTO ".$this->table." (employee_id, cell_number, status) VALUES (:employee_id, :cell, :status)");
        while ($str = fgets($readfile, 1024)) {
            //$items = explode(chr(9), $str);
            $items = explode(";", $str);
            //$items[0] - ФИО
            //$items[1] - Код УИД
            //$items[2] - Номер телефона

            if (count($items) != 3) {
                $db->exec("UPDATE ".$this->table." SET status = status*(-1)"); //возвращаем статусы
                echo "file is not in the correct format\n\r";
                break;
            }
            if (stristr($str, 'Абонент') > '') continue;

            $str_cell = str_replace('-', '', $items[2]);
            $str_cell = str_replace(chr(13).chr(10), '', $str_cell);
            
            $cells->execute(['cell_number' => $str_cell]);
            $employees->execute(['unique_1c_number' => $items[1]]);

            $cell = $cells->fetch(PDO::FETCH_LAZY);                
            $emp = $employees->fetch(PDO::FETCH_LAZY);

            if ($cell){ //если номер есть в базе
                if (!$emp) {echo $items[1] . " " .iconv('utf-8', 'cp866', $items[0]) ." not found\n\r"; continue;} //если нет сотрудника - продолжаем
                //если сотрудник есть
                if ($cell->employee_id != $emp->id) { //и номер не соответствует сотруднику
                    //echo $str_cell . " add cell to employee ". $emp->id ."\n\r";
                    $count_pre->execute(['employee_id' => $emp->id]); //выясняем сколько уже номеров у сотрудника
                    $cell_count = $count_pre->fetch(PDO::FETCH_LAZY);
                    $status = $cell_count[0]+1; //готовим статус для номера и назначаем его соответствующему сотруднику
                    $update_cells->execute(['emp_id' => (int)$emp->id, 'status' => $status, 'cell' => $str_cell]);
                } else { //если номер соответствует сотруднику
                    //echo $str_cell . "update employees cells" . $emp->id ."\n\r";
                    $status = (int)$cell->status * (-1); //готовим статус, записываем
                    $update_cells_status->execute(['status' => $status, 'cell' => $str_cell]);
                }
            } else { //если номера в базе нет
                echo $str_cell . " add new cell ";
                $status = NULL;
                $employee_id = NULL;
                if ($emp) { //если сотрудник найден
                    //готовим данные по сотруднику
                    $count_pre->execute(['employee_id' => $emp->id]);
                    $cell_count = $count_pre->fetch(PDO::FETCH_LAZY);
                    $status = $cell_count[0] + 1;
                    $employee_id = (int)$emp->id;
                    echo "to employee ". $emp->id;
                }
                echo "\n\r";
                //записываем
                if ($insert_cells->execute([
                    'employee_id' => $employee_id,
                    'cell' => $str_cell,
                    'status' => $status
                ])) {} else echo "NOT INSERT cell\n\r";
            }
        }
        //номерам, не попавшим в обработку (статус < 0), удаляем владельцев и сбрасываем статус на 1
        $db->exec("UPDATE ".$this->table." SET employee_id = NULL, status = 1 WHERE status < 0");
    } //ReadFileCells()

    /**
     * Загружаем данные по сотрудникам из файла.
     * Поле snp используется для заполнения полей name, surname и patronymic
     * @return \yii\web\Response
     */
    public function ReadfileEmployees(){
        echo "". $this->dsn . "; " . $this->user . "; ". $this->pass . "\n\r";
        echo "execute Employees\n\r";
        $db = $this->db;
        //$db = new PDO($this->dsn, $this->user, $this->pass);
        $filename = __DIR__ . '/../../backend/web/in/employees.txt';
        $readfile = fopen($filename, 'r');
        //отмечаем всех сотрудников "уволенными" после обработки они останутся таковыми
        //если не будут обнаружены в файле загрузки
        $db->exec("UPDATE employees SET status = 0, employee_number = ''");
        //запрос на получение идентификатор польоватебя по ФИО
        $employees = $db->prepare("SELECT id FROM employees WHERE unique_1c_number = ?");
        //$employees = $db->prepare("SELECT id FROM employees WHERE snp = ?");
        //запрос на добавление нового пользователя
        $new_employee = $db->prepare("INSERT INTO employees (snp, surname, name, patronymic, employee_number, branch_id, job_title, unique_1c_number) VALUES (:snp, :surname, :name, :patronymic, :employee_number, :branch_id, :job_title, :unique_1c_number)");
        //запрос на получение идентификатора подразделения по его наименованию
        $branches = $db->prepare("SELECT id FROM branches WHERE branch_title = ?");
        //запрос на обновление данных о пользователе
        $update_employee = $db->prepare("UPDATE employees SET unique_1c_number = :unique_1c_number, employee_number = :employee_number, status = 1 WHERE id = :id");
        //запрос на "увольнение" сотрудника
        //$del_employee = $db->prepare("UPDATE employees SET status = 0 WHERE id = ?");
        while ($str = fgets($readfile, 1024)){

            $items = explode(";", $str);
            //$items[0] - ФИО
            //$items[1] - Должность
            //$items[2] - Подразделение
            //$items[3] - Код
            //$items[4] - Код
            //$items[5] - Дата
            //$items[6] - Дата
            //$items[7] - Код УИД
            if (count($items) != 8) {echo "another file format\n\r"; break;}
            if ($items[1] == 'Код') continue;

            //echo iconv_strlen($items[0]) . " ";
            $employees->execute([str_replace(chr(13).chr(10), "", $items[7])]);
            //$employees->execute([$items[0]]);
            $emp = $employees->fetch(PDO::FETCH_LAZY);
            //var_dump($emp);
            //echo "\n\r";
            if($emp) {
                // обновляем уникальный номер сотрудника
                if ($emp->id == 4205)
                    echo 'update Employee ' . $emp->id . "\n\r";
                if ($update_employee->execute([
                    'unique_1c_number' => str_replace(chr(13).chr(10), "", $items[7]),
                    'employee_number' => $items[3],
                    'id' => (int)$emp->id,
                ])) {} else echo "NOT UPDATE! ";
            } else {
                echo "\n\r new Employee " . $items[7];
                //получаем ФИО
                $_snp = $items[0];
                //Разбираем ФИО на части    
                $snp = explode(" ", $items[0]);
                
                $_surname = $snp[0];
                $_name = $snp[1];
                $_patronymic = $snp[2];

                $_employee_number = $items[3];
                $branches->execute([$items[2]]);
                $branch = $branches->fetch(PDO::FETCH_LAZY);
                if ($branch) {
                    $_branch_id = $branch->id;
                } else {
                    $_branch_id = 0; 
                }
                $_job_title = $items[1];
                $_unique_1c_number = str_replace(chr(13).chr(10), "", $items[7]);
                
                if ($new_employee->execute([
                    'snp' => $_snp,
                    'surname' => $_surname,
                    'name' => $_name,
                    'patronymic' => $_patronymic,
                    'employee_number' => $_employee_number,
                    'branch_id' => $_branch_id,
                    'job_title' => $_job_title,
                    'unique_1c_number' => $_unique_1c_number
                ])) {} else echo "NOT INSERT! ";
            }
        }
        fclose($readfile);
    } //ReadfileEmployees()

    /**
     * Функция ищет уволенных сотрудников и проверяет закреплены ли они за рабочим местом
     * если закреплены, то формирует сообщение для администратора с просьбой принять меры
     */
    public function EmployeesDelete(){
        $db = $this->db;
        //запрос на получение всех ИД сотрудников со стасусом 0 (уволенные)
        $emp_status0 = $db->prepare("
            SELECT e.id id, snp, workplace_id, w.status status FROM employees e
            LEFT JOIN wp_owners w ON w.employee_id = e.id
            WHERE e.status = 0 AND workplace_id > 0 ORDER BY e.id
          ");

        //запрос для создания сообщения администратору
        $message = $db->prepare("
            INSERT INTO tasks (user_id, subject, content, target, target_id)
            VALUES (1, 'Отсутствует сотрудник', :content, :target, :target_id);
            INSERT INTO tasks (user_id, subject, content, target, target_id)
            VALUES (5, 'Отсутствует сотрудник', :content, :target, :target_id);
        ");

        $emp_status0->execute();
        while ($emp = $emp_status0->fetch(PDO::FETCH_LAZY)){
            echo $emp->id ." ". iconv('utf-8', 'cp866', $emp->snp) ." ". $emp->workplace_id ." ". $emp->status ."\n\r";
            //echo $emp_id->id ." ". $emp_id->snp ." ". $emp_id->workplace_id ." ". $emp_id->status ."\n\r";
            $message->execute([
                'target' => 'workplaces/view',
                'target_id' => $emp->workplace_id,
                'content' => 'Cотрудник <b><a href="/admin/employees/view?id='. $emp->id .'">'.$emp->snp.'</a></b> '
                    . 'закрепленый за рабочим местом: <b><a href="/admin/workplaces/view?id='. $emp->workplace_id.'">№'. $emp->workplace_id.'</a></b> '
                    . 'со статусом: <b>'. $emp->status .'</b> (1 - основной, 2 - второстепенный)<br />'
                    . 'Отсутствует в свежем списке згрузки сотрудников.',
            ]);
        }
    } //EmployeesDelete()
}

$main = new Main();
$main->DwnFtp(); //качаем исходные файлы
$main->Connect(); //цепляеся к базе
$main->ReadfileEmployees(); //заполняем таблицу сотрудников
$main->ReadFileCells(); //заполняем таблицу телефонов
$main->EmployeesDelete(); //проверяем уволенных