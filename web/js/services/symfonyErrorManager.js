/**
 * Created by Antoine on 27/03/2017.
 */
module.exports = function($log, $parse, $sce) {

    /**
     * Read an http content error and return true if it's a symfony form errors
     * @param error
     * @returns {*}
     */
    this.isFormError = function(error) {
        if(angular.isUndefined(error))
            return false;
        return angular.isDefined(error.form) && angular.isDefined(error.errors)
    };

    /**
     * Read an http content error and return true if it's some symfony exception
     * @param error
     * @returns {*}
     */
    this.isRequestError = function(error) {
        if(angular.isUndefined(error))
            return false;

        return angular.isDefined(error.error) && angular.isDefined(error.error.code);
    };

    /**
     *
     * Return Root Form errors
     * @returns {Array}
     */
    this.getFormError = function(error) {
        if(!this.isFormError(error))
            return [];
        return error.form.errors;
    };

    /**
     *
     * Return Specific Input Form errors
     * @returns {Array}
     */
    this.getInputError = function(name, error) {
        if(!this.hasInputError(name, error))
            return [];

        let getter = $parse(name);
        let context = error.form.children;

        let input = getter(context);

        return input.errors;
    };


    this.hasInputError = function(name, error) {

        if(!this.isFormError(error))
            return false;

        let getter = $parse(name);
        let context = error.form.children;

        let input = getter(context);

        return !angular.isUndefined(input.errors);

    };

        /**
     *
     * @param name
     * @param error
     * @returns {*}
     */
    this.getHtmlInputError = function(name, error) {
        if(!this.hasInputError(name, error))
            return "";

        let errors = this.getInputError(name, error);

        let getter = $parse(name + ".htmlErrors");
        let setter = getter.assign;
        let context = error.form.children;

        if(angular.isUndefined(getter(context))) {
            let htmlErrors = "";
            angular.forEach(errors, function(error) {
                htmlErrors += error + '<br/>';
            });
            setter(context, $sce.trustAsHtml(htmlErrors));
        }

        return getter(context);
    };

};