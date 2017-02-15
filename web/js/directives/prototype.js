/**
 * Created by Antoine on 12/02/2017.
 */
/*
 * angular directive for data-prototype on Symfony Form Collection type
 */
module.exports = function($log) {
    return {
        restrict: 'A',
        scope: {
            prototype:"@",
            what:"=",
            length:"=",
        },
        link: function(scope, element, attributes){
            let what = "";

            if(angular.isDefined(scope.what))
                what = scope.what;

            let addItemButton = '<button type="button" class="btn btn-sm btn-success"><span class="glyphicon glyphicon-plus"></span></button>';

            element.append(addItemButton);

            element.find('button').on('click', function(event) {
                let clone = angular.element(scope.prototype).clone().html();

                clone = clone
                    .replace(/__name__label__/g, what)
                    .replace(/__name__/g, element.find('input').length)
                ;
                element.find(angular.element(scope.prototype).prop('tagName')).last().after(clone);
            })
        }
    }
};