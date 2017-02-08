/**
 * Created by Antoine on 08/02/2017.
 */
module.exports = function($log, rest) {
    return {
        restrict: 'A',
        scope: {
            typeahead:"=",
            url: '=',
        },
        link: function(scope, element, attributes){
            console.log(scope.typeahead, scope.url);

            rest.get(scope.url, function(success) {

                let usernames = success.data;

                //TODO: peut-etre retravailler cette partie pour permettre l'auto-complétion d'objets....
                let substringMatcher = function(strs) {
                    return function findMatches(q, cb) {
                        let matches, substrRegex;

                        // an array that will be populated with substring matches
                        matches = [];

                        // regex used to determine if a string contains the substring `q`
                        substrRegex = new RegExp(q, 'i');

                        // iterate through the pool of strings and for any string that
                        // contains the substring `q`, add it to the `matches` array
                        $.each(strs, function(i, str) {
                            if (substrRegex.test(str)) {
                                matches.push(str);
                            }
                        });

                        cb(matches);
                    };
                };

                element.typeahead({
                        hint: true,
                        highlight: true,
                        minLength: 1
                    },
                    {
                        name: scope.typeahead,
                        source: substringMatcher(usernames)
                    });

            });
        }
    };
};