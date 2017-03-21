/**
 * Created by Antoine on 16/03/2017.
 * Object used to store some datas on bdd via rest service.
 */
function PersistentObject(route, options, formDatas, config, viewState) {
    const UN_PERSISTED = config.persistentStates.UN_PERSISTED;
    const PERSISTED = config.persistentStates.PERSISTED;
    const ON_PERSIST = config.persistentStates.ON_PERSIST;
    const ERROR_PERSIST = config.persistentStates.ERROR_PERSIST;

    this.route = route;
    this.options = options;
    this.formDatas = formDatas;
    this.state = UN_PERSISTED;

    /**
     * viewState looks like:
     * {
     *      title,
     *      subtitles,
     *      content-panel html (no more datas than a panel),
     *      state: unpersisted|persisted|error
     * }
     */
    this.viewState = viewState;

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

        this.state = ON_PERSIST;

        rest.post(this.route, this.options, this.formDatas, function(success) {
            self.state = PERSISTED;

            if(angular.isDefined(onRestSuccess))
                onRestSuccess(success);

            if(angular.isDefined(self.onSuccess))
                self.onSuccess(success);

        }, function(error) {
            self.state = ERROR_PERSIST;
            if(angular.isDefined(onRestError)) {
                onRestError(error);
            }

            if(angular.isDefined(self.onFailure))
                self.onFailure(error);
        });
    };
}