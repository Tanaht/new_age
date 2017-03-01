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
            allowAdd:"=",
            allowDelete:"=",
            what:"=",
        },
        link: function(scope, element, attributes){
            let what = "";
            if(angular.isDefined(scope.what))
                what = scope.what;

            let removeItemButton = '<button type="button" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span></button>';

            //Method requested when user click on the red cancel button (the event pass the item to delete in parameters
            scope.deleteClickListener = function(event) {
                event.data.item.remove();
            };

            //Method requested when user click on the green plus button
            scope.addClickListener = function(event) {
                let clone = angular.element(scope.prototype).clone().html();

                clone = clone
                    .replace(/__name__label__/g, what)
                    .replace(/__name__/g, length++)
                ;

                if(angular.isDefined(scope.allowDelete)) {
                    let removeItemButtonCloned = angular.element(removeItemButton).clone();

                    clone = angular.element(clone).append(removeItemButtonCloned);

                    removeItemButtonCloned.on('click', {item: clone }, function (event) {
                        scope.deleteClickListener(event)
                    });
                }

                if(length > 1)
                    element.find('[collection-item]').last().after(clone);
                else
                    element.find("button.typeahead-add-btn").before(clone);
            };


            //Add listener to elements if collection allow add event.
            if(angular.isDefined(scope.allowAdd)) {
                let addItemButton = '<button type="button" class="typeahead-add-btn btn btn-sm btn-success"><span class="glyphicon glyphicon-plus"></span></button>';
                let length = element.find('[collection-item]').length;

                element.append(addItemButton);

                element.find('button').on('click', function(event) {
                    scope.addClickListener(event)
                });
            }

            //Add listener to elements if collection allow delete event.
            if(angular.isDefined(scope.allowDelete)) {
                angular.forEach(element.find('[collection-item]'), function(collectionItem, key) {
                    let removeItemButtonClonednoConflict = angular.element(removeItemButton).clone();

                    angular.element(collectionItem).append(removeItemButtonClonednoConflict);

                    removeItemButtonClonednoConflict.on('click', {item: collectionItem },  function (event) {
                        scope.deleteClickListener(event);
                    });
                });
            }
        }
    }
};