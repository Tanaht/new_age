/**
 * Created by Antoine on 16/03/2017.
 * Object used to store some datas on bdd via rest service.
 */
function PersistentObject(route, options, formDatas) {
    /*
     * TODO: [WARNING] bad performance issue, creating a factory of PersistentObject in angular would be a greater idea (injection is very bad when so common instantions are requested).
     */
    let $injector = angular.element('body[data-ng-app]').injector();

    //let $compile = $injector.get('$compile');
    let $log = $injector.get('$log');
    let config = $injector.get('config');
    let router = $injector.get('router');

    const UN_PERSISTED = config.persistentStates.UN_PERSISTED;
    const PERSISTED = config.persistentStates.PERSISTED;
    const ON_PERSIST = config.persistentStates.ON_PERSIST;
    const ERROR_PERSIST = config.persistentStates.ERROR_PERSIST;

    this.icon = 'floppy-disk';
    this.alert = 'info';
    this.route = route;
    this.options = options;
    this.formDatas = formDatas;
    this.state = UN_PERSISTED;

    this.updateState = function(newState) {
        switch(newState) {
            case UN_PERSISTED:
                this.icon='floppy-disk';
                this.alert = 'info';
            break;
            case PERSISTED:
                this.icon='floppy-saved';
                this.alert = 'success';
            break;
            case ON_PERSIST:
                this.icon='refresh';
                this.alert = 'warning';
            break;
            case ERROR_PERSIST:
                this.icon='floppy-removed';
                this.alert = 'danger';
            break;
            default:
                return;
        }

        this.state = newState;
    };

    /**
     * Default message to give user an idea of what this is.
     * @type {string}
     */
    this.message = function() {
        return "[Queue:POST]" + router.generate(this.route, this.options);
    };

    /**
     *
     * @param error AJAX error
     * @returns {string}
     */
    this.errorModal = function(error) {
        return "Une Erreur est survenue.";
    };

    /**
     *
     * @param infoMessage {string|function($compile)}
     * @param errorModal {string|function($compile)}
     */
    this.setMessages = function(infoMessage, errorModal) {
        if(angular.isDefined(infoMessage) && angular.isFunction(infoMessage)) {
            this.message = infoMessage;
        }

        if(angular.isDefined(errorModal)  && angular.isFunction(errorModal)) {
            this.errorModal = errorModal;
        }
    };

    /**
     * Triggered on Failure of persist()
     */
    this.onFailure = undefined;

    /**
     * Triggered on Success of persist()
     */
    this.onSuccess = undefined;

    /**
     * Update on Failure method
     */
    this.setOnFailure = function(onFailure) {
        this.onFailure = onFailure;
    };

    /**
     * Update on Success method
     */
    this.setOnSuccess = function(onSuccess) {
        this.onSuccess = onSuccess;
    };

    /**
     * persist this PersistentObject to bdd via angular rest services
     * @return true|false
     */
    this.persist = function(rest, onRestSuccess, onRestError) {
        let self = this;

        this.updateState(ON_PERSIST);

        rest.post(this.route, this.options, this.formDatas, function(success) {
            self.updateState(PERSISTED);

            if(angular.isDefined(onRestSuccess))
                onRestSuccess(success);

            if(angular.isDefined(self.onSuccess))
                self.onSuccess(success);

        }, function(error) {
            self.updateState(ERROR_PERSIST);

            //call PersistedStateView directive that manage this service
            if(angular.isDefined(onRestError)) {
                onRestError(error);
            }

            //update error modal
            if(angular.isDefined(self.errorModal)) {
                self.errorModal(error);
            }

            //call some directives|controllers|services that instantiates persistentObject
            if(angular.isDefined(self.onFailure))
                self.onFailure(error);
        });
    };
}