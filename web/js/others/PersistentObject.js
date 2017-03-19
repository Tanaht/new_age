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

    this.hydrateCsrfToken = function(rest, onSuccess, onError) {
        console.debug("persistend object created at:", route, options);
        let self = this;
        /*
            TODO: It's not secure to tell to generate token from client with clair token id in script. A fix would be to generate a token based on the resources [routename] we try to post.
         */
        rest.get('get_csrf_token', { intention: 'voeu_form_token_id'}, function(success) {
            self.formDatas.Token = success.data;
            self.persist(rest, onSuccess, onError);
        }, function(error) {
            console.error('[PersistentObject] Unable to retrieve CsrfToken');
        });
    };
    /**
     * persist this PersistentObject to bdd via angular rest services
     * @return true|false
     */
    this.persist = function(rest, onRestSuccess, onRestError) {
        let self = this;
        if(angular.isDefined(this.formDatas.Token)) {//send formDatas to rest api
            rest.post(this.route, this.options, this.formDatas, function(success) {
                self.state = PERSISTED;
                delete self.formDatas.Token;

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
        }
        else {//hydrate token if not here
            this.state = ON_PERSIST;
            this.hydrateCsrfToken(rest, onRestSuccess, onRestError);
        }

        return false;
    };
}