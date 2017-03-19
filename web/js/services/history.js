/**
 * Created by Antoine on 16/03/2017.
 * This service is used to managed update to database
 */
module.exports = function($log, rest, config) {
    const UNPERSISTED = "UNPERSISTED";
    const PERSISTED = "PERSISTED";
    const ERROR = "ERROR";

    /**
     * History Queue
     * @type {Array}
     */
    this.history = [];

    /**
     * Push history to queue
     */
    this.push = function(object) {
        this.history.push(object);
    };


    /**
     * Get head of the queue
     * @returns undefined|PersistentObject
     */
    this.first = function() {
        if(!this.hasNext())
            return undefined;
        return this.history[0];
    };

    /**
     * poll history from queue
     */
    this.poll = function() {
        return this.history.shift();
    };


    this.hasNext = function() {
        return this.history.length != 0;
    };



    /**
     * Persist all PersistentObjects from historyQueue
     * @param onPersistedSuccess: promise callable called when all queue is persisted.
     * @param onPersistedFailure: promise callable called when an error occured.
     */
    this.persist = function(onPersistedSuccess, onPersistedFailure) {
        let self = this;
        if(!this.hasNext()) {
            if(angular.isDefined(onPersistedSuccess))
                onPersistedSuccess();
            return;
        }

        let po = this.first();

        let onSuccess = po.onSuccess;
        let onFailure = po.onFailure;


        po.setOnSuccess(function(success) {
            if(config.debugHistory && config.debugMode)
                $log.debug("[Service:history] Success Persist");

            if(angular.isDefined(onFailure))
                onSuccess(success);

            self.poll();
            self.persist();
        });

        po.setOnFailure(function(error) {
            if(config.debugHistory && config.debugMode)
                $log.error("[Service:history] Error Persist");

            if(angular.isDefined(onFailure))
                onFailure(error);

            if(angular.isDefined(onPersistedFailure))
                onPersistedFailure();
        });

        po.persist(rest);
    }
};