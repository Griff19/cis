ymaps.ready(function () {
// Максимальный зум присутствия тайлов
    var maxZoom = 4;

    var OverzoomableLayer = ymaps.util.defineClass(function OverzoomableLayer(tileUrlTemplate, options) {
        OverzoomableLayer.superclass.constructor.call(this, tileUrlTemplate, options);
    }, ymaps.Layer, {
        getTileUrl: function (number, zoom) {
            return OverzoomableLayer.superclass.getTileUrl.call(this, number, maxZoom? Math.min(zoom, maxZoom) : zoom);
        },
        getTileSize: function(zoom){

            var originalSize = OverzoomableLayer.superclass.getTileSize.call(this, zoom);
            if(maxZoom && zoom > maxZoom) {

                var size = originalSize[0] * Math.pow(2, zoom - maxZoom);
                return [size, size];
            }

            return originalSize;
        }});
    var LAYER_NAME = 'user#layer',
        MAP_TYPE_NAME = 'user#customMap',
        // Директория с тайлами.
        TILES_PATH = 'https://sandbox.api.maps.yandex.net/examples/ru/2.1/custom_map/images/tiles',
        /* Для того чтобы вычислить координаты левого нижнего и правого верхнего углов прямоугольной координатной
         * области, нам необходимо знать максимальный зум, ширину и высоту изображения в пикселях на максимальном зуме.
         */
        MAX_ZOOM = 4,
        PIC_WIDTH = 2526,
        PIC_HEIGHT = 1642;

    /**
     * Конструктор, создающий собственный слой.
     */
    var layer = new OverzoomableLayer(TILES_PATH + '/%z/tile-%x-%y.jpg', {
        // Если есть необходимость показать собственное изображение в местах неподгрузившихся тайлов,
        // раскомментируйте эту строчку и укажите ссылку на изображение.
        // notFoundTile: 'url'
    });
    var Layer = function () {

        // Указываем доступный диапазон масштабов для данного слоя.
        layer.getZoomRange = function () {
            return ymaps.vow.resolve([0, 10]);
        };
        // Добавляем свои копирайты.
        layer.getCopyrights = function () {
            return ymaps.vow.resolve('©');
        };
        return layer;
    };
    // Добавляем в хранилище слоев свой конструктор.
    ymaps.layer.storage.add(LAYER_NAME, Layer);

    /**
     * Создадим новый тип карты.
     * MAP_TYPE_NAME - имя нового типа.
     * LAYER_NAME - ключ в хранилище слоев или функция конструктор.
     */
    var mapType = new ymaps.MapType(MAP_TYPE_NAME, [LAYER_NAME]);
    // Сохраняем тип в хранилище типов.
    ymaps.mapType.storage.add(MAP_TYPE_NAME, mapType);

    // Вычисляем размер всех тайлов на максимальном зуме.
    var worldSize = Math.pow(2, MAX_ZOOM) * 256,
        /**
         * Создаем карту, указав свой новый тип карты.
         */
        map = new ymaps.Map('map', {
            center: [0, 0],
            zoom: 2,
            controls: ['zoomControl'],
            type: MAP_TYPE_NAME
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

    // Ставим метку в центр координат. Обратите внимание, координаты метки задаются в порядке [y, x].
    var point = new ymaps.Placemark([0, 0], {
        balloonContent: 'Координаты метки: [0, 0]'
    }, {
        preset: 'islands#darkOrangeDotIcon'
    });

    map.geoObjects.add(point);
});
