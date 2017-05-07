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
    let $q = $injector.get('$q');
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
    this.initialDatas = angular.copy(datas);
    this.error = {};//An HTTP error when this.state == ERROR_PERSIST;
    this.state = UN_PERSISTED;

    this.persistErrorHandled = false;
    this.templateUrl = undefined;
    this.scope = undefined;

    /**
     * Track the number of times when this object has been persisted
     * @type {number}
     */
    this.persistedCount = 0;
    /**
     * Used to allow to change the route if this object has been persisted a certain number of time
     * @type {Array}
     */
    this.routeGuesser = {};

    this.updateRoute = function() {

        if (this.routeGuesser.hasOwnProperty(this.persistedCount)) {
            this.route = this.routeGuesser[this.persistedCount].route;
            this.options = this.routeGuesser[this.persistedCount].options;
        }
        else if (this.routeGuesser.hasOwnProperty("infinity")) {
            this.route = this.routeGuesser["infinity"].route;
            this.options = this.routeGuesser["infinity"].options;
        }

        //$log.debug(this.route, this.options);
    };

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
     * @param infoMessage {function}
     */
    this.setMessageCallback = function(infoMessage) {
        if(angular.isDefined(infoMessage) && angular.isFunction(infoMessage)) {
            this.message = infoMessage;
        }
        else {
            $log.error("setMessageCallback needs a function in parameter");
        }
    };

    /**
     * En cas d'erreur de requête permettre à l'objet Persistent de prendre en charge l'erreur
     * @param scope le scope original du formulaire
     * @param templateUrl l'url originale du formulaire
     */
    this.handlePersistError = function(scope, templateUrl) {
        this.scope = scope;
        this.templateUrl = templateUrl;
        this.persistErrorHandled = true;
    };

    /**
     * persist this PersistentObject to bdd via angular rest services
     * @return true|false
     */
    this.persist = function(rest) {
        let deferred = $q.defer();
        let self = this;

        this.updateState(ON_PERSIST);

        rest.post(this.route, this.options, this.datas).then(function(success) {
            self.initialDatas = angular.copy(self.datas);
            self.updateState(PERSISTED);
            self.persistedCount++;
            self.updateRoute();
            deferred.resolve(success);

        }, function(error) {
            self.updateState(ERROR_PERSIST);
            self.error = error.data;
            deferred.reject(error);
        });
        return deferred.promise;
    };

    /**
     * Tells if the persisted datas has changed since the last persist.
     * @returns {boolean}
     */
    this.hasChanged = function() {
        return !angular.equals(this.initialDatas, this.datas);
    }
}