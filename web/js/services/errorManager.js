/**
 * Created by tanna on 26/03/2017.
 */
module.exports = function($log, $parse) {
    this.isFormError = function(error) {
        if(angular.isUndefined(error))
            return false;
        return angular.isDefined(error.form) && angular.isDefined(error.errors)
    };

    this.isFormFlatten = function(error) {
        if(!this.isFormError(error))
            return false;
        angular.forEach(error.errors, function(key, value) {
            if(angular.isObject(value))
                return false;
        });
        return true;
    };

    this.isRequestError = function(error) {
        if(angular.isUndefined(error))
            return false;

        return angular.isDefined(error.error) && angular.isDefined(error.error.code);
    };

    this.getAllFormErrors = function(error) {
        if(!this.isFormFlatten(error))
            return false;

        return error.errors;
    };


    /**
     * Retrieve form errors that are not related with any of the form child.
     * @param error the whole error
     * @returns {*}
     */
    this.getFormRootErrors = function(error) {
        if(!this.isFormError(error))
            return [];

        return error.form.errors;
    };

    /**
     * Retrieve form errors that are related with the form name child.
     * @param name
     * @param error the whole error
     * @returns {*}
     */
    this.getInputErrors = function(name, error) {
        if(!this.isFormError(error))
            return [];

        let getter = $parse(name);
        let context = error.form.children;

        let input = getter(context);

        if(angular.isUndefined(input)) {
            $log.error("Cannot retrieve value:", name, " on object: ", context);
        }
        return angular.isDefined(input.errors) ? input.errors : [];
    }
};