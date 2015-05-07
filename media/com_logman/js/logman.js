var Logman = {
    Files: {
        init: function (config) {
            // Initialize variables
            if (!config) var config = {};
            if (!config.selector) config.selector = '.logman-file';

            $$(config.selector).addEvent('click', function (e) {
                e.preventDefault();

                var data = {};

                data.name = this.get('data-name');
                data.size = this.get('data-size');
                data.url = this.get('href');

                if (this.get('data-width') && this.get('data-height')) {
                    data.width = this.get('data-width');
                    data.height = this.get('data-height');
                    data.image = true;
                }

                var element = new Element('div', {html: new EJS({element: $('logman-file-template')}).render(data)});
                element.setStyle('display', 'inline-block').addClass('com_logman');
                var output = element.inject($('logman-file-tmp'));

                var display = function () {
                    var size = output.measure(function () {
                        return this.getSize();
                    });

                    SqueezeBox.open(output, {handler: 'adopt', size: {x: size.x, y: size.y}});
                };

                display.delay(100);
            });
        }
    }
};