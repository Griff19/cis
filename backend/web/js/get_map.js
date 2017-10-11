var map;
ymaps.ready(function () {

    var LAYER_NAME = 'user#layer',
        MAP_TYPE_NAME = 'user#customMap',
        
        TILES_PATH = '/admin/map/'+ branch+ '_' + floor,

        MAX_ZOOM = max_zoom,
        PIC_WIDTH = pic_width,
        PIC_HEIGHT = pic_height;

    //Создаем свой слой.
    var Layer = function () {
        var layer = new ymaps.Layer(TILES_PATH + '/%z/tile-%x-%y.png', {
            // Если есть необходимость показать собственное изображение в местах неподгрузившихся тайлов,
            // раскомментируйте эту строчку и укажите ссылку на изображение.
            // notFoundTile: 'url'
        });
        // Указываем доступный диапазон масштабов для данного слоя.
        layer.getZoomRange = function () {
            return ymaps.vow.resolve([0, 6]);
        };
        // Добавляем свои копирайты.
        layer.getCopyrights = function () {
            return ymaps.vow.resolve('© Алтайская Буренка');
        };
        return layer;
    };
    // Добавляем в хранилище слоев свой конструктор.
    ymaps.layer.storage.add(LAYER_NAME, Layer);

    var mapType = new ymaps.MapType(MAP_TYPE_NAME, [LAYER_NAME]);
    // Сохраняем тип в хранилище типов.
    ymaps.mapType.storage.add(MAP_TYPE_NAME, mapType);

    var centerY = 0;
    var centerX = 0;

    if (typeof (points) != "undefined" && points.length > 0) {
        centerY = points[0].y;
        centerX = points[0].x;
    };

    // Вычисляем размер всех тайлов на максимальном зуме.
    var worldSize = Math.pow(2, MAX_ZOOM) * 256,
        //Создаем карту, указав свой новый тип карты.
        map = new ymaps.Map('map', {
            center: [centerY, centerX],
            zoom: 4,
            controls: ['zoomControl'],
            type: MAP_TYPE_NAME,

        }, {

            // Задаем в качестве проекции Декартову. При данном расчёте центр изображения будет лежать в координатах [0, 0].
            projection: new ymaps.projection.Cartesian([[PIC_HEIGHT / 2 - worldSize, -PIC_WIDTH / 2], [PIC_HEIGHT / 2, worldSize - PIC_WIDTH / 2]], [false, false]),
            // Устанавливаем область просмотра карты так, чтобы пользователь не смог выйти за пределы изображения.
            restrictMapArea: [[-PIC_HEIGHT / 2, -PIC_WIDTH / 2], [PIC_HEIGHT / 2, PIC_WIDTH / 2]]

            // При данном расчёте, в координатах [0, 0] будет находиться левый нижний угол изображения,
            // правый верхний будет находиться в координатах [PIC_HEIGHT, PIC_WIDTH].
            // projection: new ymaps.projection.Cartesian([[PIC_HEIGHT - worldSize, 0], [PIC_HEIGHT, worldSize]], [false, false]),
            // restrictMapArea: [[0, 0], [PIC_HEIGHT, PIC_WIDTH]]
        });
    map.cursors.push('crosshair');


    //получаем метки из представления где будет отображаться карта
    if (typeof (points) != "undefined") {
        for (let value of points) {

            var point = new ymaps.Placemark([value.y, value.x], {
                balloonContent: value.balloonContent,
                iconContent: value.content,
                iconCaption: value.content
            }, {
                preset: value.preset
            });

            map.geoObjects.add(point);
        }
    }

    if (typeof (edit) != "undefined") {
        var myPlacemark;
        map.events.add('click', function (e) {
            // Получение координат щелчка
            var coords = e.get('coords');
            $('#coordinate-y').val(coords[0]);
            $('#coordinate-x').val(coords[1]);

            if (myPlacemark) {
                myPlacemark.geometry.setCoordinates(coords);
            }
            // Если нет – создаем.
            else {
                myPlacemark = new ymaps.Placemark(coords, {
                    iconCaption: 'поиск...'
                }, {
                    preset: 'islands#dotIcon',
                    draggable: true
                });
                map.geoObjects.add(myPlacemark);
            }
        });
    }
});

