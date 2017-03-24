/**
 * Created by Antoine on 16/03/2017.
 * Object used to store some datas on bdd via rest service.
 */
function PersistentObject(route, routing_options, datas) {
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
    this.options = routing_options;
    this.datas = datas;
    this.state = UN_PERSISTED;

    this.persistErrorHandled = false;
    this.templateUrl = undefined;
    this.scope = undefined;


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
     * @param infoMessage {string}
     */
    this.setMessageCallback = function(infoMessage) {
        if(angular.isDefined(infoMessage) && angular.isFunction(infoMessage)) {
            this.message = infoMessage;
        }
        else {
            $log.error("setMessageCallback needs a function in parameter");
        }
    };

    this.handlePersistError = function(scope, templateUrl) {
        this.scope = scope;
        this.templateUrl = templateUrl;
        this.persistErrorHandled = true;
    };

    /**
     * persist this PersistentObject to bdd via angular rest services
     * @return true|false
     */
    this.persist = function(rest, onRestSuccess, onRestError) {
        let self = this;

        this.updateState(ON_PERSIST);

        rest.post(this.route, this.options, this.datas, function(success) {
            self.updateState(PERSISTED);

            if(angular.isDefined(onRestSuccess))
                onRestSuccess(success);

        }, function(error) {
            self.updateState(ERROR_PERSIST);
            self.scope.persistError = error.data;
            //call PersistedStateView directive that manage this service
            if(angular.isDefined(onRestError)) {
                onRestError(error);
            }
        });
    };
}