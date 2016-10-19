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
 * @property  string dsn
 * @property  string user
 * @property  string pass
 */
class Main
{
    private static $dsn;
    private static $user;
    private static $pass;

    function __construct(){
        $params = require(__DIR__ . '/../config/main-local.php');
        $this->dsn = $params['components']['db']['dsn'];
        $this->user = $params['components']['db']['username'];
        $this->pass = $params['components']['db']['password'];
    }

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
     * Обрабатываем загруженный файл
     */
    public function ReadFileCells()
    {
        echo 'read file'. "\n\r";
        $filename = __DIR__ . '/../../backend/web/in/cellnumbers.txt';

        $readfile = fopen($filename, 'r');

        $db = new PDO($this->dsn, $this->user, $this->pass);
        //инвертируем статус. номера, не прошедшие обработку, останутся с отрицательным статусом
        $db->exec("UPDATE cell_numbers SET status = status*(-1)");

        $cells = $db->prepare("SELECT employee_id, status FROM cell_numbers WHERE cell_number = ?");
        //запрос получения идентификатора пользователя по УИД
        $employees = $db->prepare("SELECT id FROM employees WHERE unique_1c_number = ?");
        //запрос наличия телефонных номеров у сотрудника
        $count_pre = $db->prepare("SELECT COUNT(*) FROM cell_numbers WHERE employee_id = ? ");
        //запрос на обновление пользователя телефонного номера
        $update_cells = $db->prepare("UPDATE cell_numbers SET employee_id = :emp_id, status = :status WHERE cell_number = :cell");
        //запрос на обновление статуса телефонного номера
        $update_cells_status = $db->prepare("UPDATE cell_numbers SET status = :status WHERE cell_number = :cell");
        //добавляем новый номер телефона
        $insert_cells = $db->prepare("INSERT INTO cell_numbers (employee_id, cell_number, status) VALUES (:employee_id, :cell, :status)");
        while ($str = fgets($readfile, 1024)) {
            //$items = explode(chr(9), $str);
            $items = explode(";", $str);
            //$items[0] - ФИО
            //$items[1] - Код УИД
            //$items[2] - Номер телефона

            if (count($items) != 3) {
                $db->exec("UPDATE cell_numbers SET status = status*(-1)"); //возвращаем статусы
                //echo 'file is not in the correct format';
                break;
            }
            if (stristr($str, 'Абонент') > '') continue;

            $str_cell = str_replace('-', '', $items[2]);
            
            $cells->execute([$str_cell]);
            $employees->execute([$items[1]]);

            $cell = $cells->fetch(PDO::FETCH_LAZY);                
            $emp = $employees->fetch(PDO::FETCH_LAZY);

            if ($cell){ //если номер есть в базе
                if (!$emp) continue; //если нет сотрудника - продолжаем
                //если сотрудник есть
                if ($cell->employee_id != $emp->id) { //и номер не соответствует сотруднику
                    $count_pre->execute([$emp->id]); //выясняем сколько уже номеров у сотрудника
                    $cell_count = $count_pre->fetch(PDO::FETCH_LAZY);
                    $status = $cell_count[0]+1; //готовим статус для номера и назначаем его соответствующему сотруднику
                    $update_cells->execute(['emp_id' => (int)$emp->id, 'status' => $status, 'cell' => $str_cell]);
                } else { //если номер соответствует сотруднику
                    $status = (int)$cell->status * (-1); //готовим статус, записываем
                    $update_cells_status->execute(['status' => $status, 'cell' => $str_cell]);
                }

            } else { //если номера в базе нет
                $status = '';
                $employee_id = '';
                if ($emp) { //если сотрудник найден
                    //готовим данные по сотруднику
                    $count_pre->execute([$emp->id]);
                    $cell_count = $count_pre->fetch(PDO::FETCH_LAZY);
                    $status = $cell_count[0] + 1;
                    $employee_id = $emp->id;
                }
                //записываем
                $insert_cells->execute(['employee_id' => $employee_id, 'cell' => $str_cell, 'status' => $status]);
            }
        }
        //номерам, не попавшим в обработку (статус < 0), удаляем владельцев и сбрасываем статус на 1
        $db->exec("UPDATE cell_numbers SET employee_id = NULL, status = 1 WHERE status < 0");
    } //ReadFileCells()

    /**
     * Загружаем данные по сотрудникам из файла.
     * Поле snp используется для заполнения полей name, surname и patronymic
     * @return \yii\web\Response
     */
    public function ReadfileEmployees(){
        echo 'execute Employees';
        $db = new PDO($this->dsn, $this->user, $this->pass);
        $filename = __DIR__ . '/../../backend/web/in/employees.txt';
        $readfile = fopen($filename, 'r');
        //запрос на получение идентификатор польоватебя по ФИО
        $employees = $db->prepare("SELECT id FROM employees WHERE snp = ?");
        //запрос на добавление нового пользователя
        $new_employee = $db->prepare("INSERT INTO employees (snp, surname, name, patronymic, employee_number, branch_id, job_title, unique_1c_number) VALUES (:snp, :surname, :name, :patronymic, :employee_number, :branch_id, :job_title, :unique_1c_number)");
        //запрос на получение идентификатора подразделения по его наименованию
        $branches = $db->prepare("SELECT id FROM branches WHERE branch_title = ?");
        //запрос на обновление данных о пользователе
        $update_employee = $db->prepare("UPDATE employees SET unique_1c_number = :unique_1c_number WHERE id = :id");
        while ($str = fgets($readfile, 1024)){
            $items = explode(chr(9), $str);
            //$items[0] - ФИО
            //$items[1] - Должность
            //$items[2] - Подразделение
            //$items[3] - Код
            //$items[4] - Код
            //$items[5] - Дата
            //$items[6] - Дата
            //$items[7] - Код УИД
            if (count($items) != 8) {break;}
            if ($items[1] == 'Код') continue;
            
            //$emp = Employees::findOne(['snp' => $items[0]]);
            $employees = $employees->execute([$items[0]]);
            $emp = $employees->fetch(PDO::FETCH_LAZY);

            if($emp) {
                // обновляем уникальный номер сотрудника
                echo 'update Employee ' . $emp->id;
                $update_employee->execute([
                    'unique_1c_number' => $items[7],
                    'id' => $emp->id,
                ]);
            } else {
                echo 'new Employee ' . $items[7];
                //получаем ФИО
                $_snp = $items[0];
                //Разбираем ФИО на части    
                $snp = explode(" ", $items[0]);
                
                $_surname = $snp[0];
                $_name = $snp[1];
                $_patronymic = $snp[2];

                $_employee_number = $items[1];
                $branches = $branches->execute([$items[2]]);
                $branch = $branches->fetch(PDO::FETCH_LAZY);
                if ($branch) {
                    $_branch_id = $branch->id;
                } else {
                    $_branch_id = 0; 
                }
                $_job_title = $items[1];
                $_unique_1c_number = $items[7];
                
                $new_employee->execute([
                    'snp' => $_snp,
                    'surname' => $_surname,
                    'name' => $_name,
                    'patronymic' => $_patronymic,
                    'employee_number' => $_patronymic,
                    'branch_id' => $_branch_id,
                    'job_title' => $_job_title,
                    'unique_1c_number' => $_unique_1c_number
                ]);
            }
        }
        fclose($readfile);
    } //ReadfileEmployees()
}

$main = new Main();
$main->DwnFtp();
$main->ReadfileEmployees();
//$main->ReadFileCells();