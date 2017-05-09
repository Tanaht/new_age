/**
 * Created by Antoine on 21/03/2017.
 */
module.exports = function($log, config) {
    return {
        restrict : 'E',
        templateUrl: config.base_uri + '/js/tpl/etape_view.tpl.html',
        scope: {
            etape: '='
        },
    }
};