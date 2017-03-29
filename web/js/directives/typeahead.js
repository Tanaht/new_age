/**
 * Created by Antoine on 08/02/2017.
 */
module.exports = function($log, config) {
    return {
        restrict: 'A',
        scope: {
            typeahead:"=",
            display:"@",
            url: '@',
            eventSuffix: "@",
            options: "=",
        },
        link: function(scope, element, attributes){
            let searcher = new Bloodhound({
                datumTokenizer: function(datum) {//TODO: this is a fix to allow real autocomplete maybe to costly.
                    let tokens = Bloodhound.tokenizers.whitespace(datum[scope.display]);
                    $.each(tokens,function(k,v){

                        for(let i = 1 ; i < v.length ; i++) {
                            tokens.push(v.substr(i,v.length));
                        }
                    });
                    //$log.debug(tokens);
                    return tokens;
                },
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                prefetch: {
                    url: scope.url,
                    cache: false
                },
                identify: function(objet) {
                    if(angular.isUndefined(objet.id))
                        $log.error('[directives:typeahead]Cannot retrieve id property of items at ' + scope.url);
                    return objet.id;
                },
                /*remote: {
                    url: '../data/films/queries/%QUERY.json',
                    wildcard: '%QUERY'
                }*/
            });

            element.typeahead({
                showHintOnFocus: true,
                displayText: function(object){ return object[scope.display];},
                source: searcher.ttAdapter(),
                updater: function(selectedValue) {
                    //if(config.debugMode)
                    //    $log.debug("Typeahead event :" + selectedValue);
                    scope.select(selectedValue);
                    return selectedValue;
                }
            })
            ;
        },
        controller: function($scope) {

            if(config.debugMode)
                $log.debug(config);

            $scope.eventName = "typeahead";

            if(angular.isDefined($scope.eventSuffix)) {
                $scope.eventName += ':' +  $scope.eventSuffix;
            }

            $scope.select = function(selectedValue) {
                $log.debug("Typeahead find that object:", selectedValue);
                $scope.$emit($scope.eventName, {object: selectedValue, options: $scope.options });
            }
        }
    };
};