import Vue from 'vue';
import Vuex from 'vuex';
import createPersistedState from 'vuex-persistedstate';
import Cookies from 'js-cookie';

Vue.use(Vuex);

export default new Vuex.Store({
    name: 'store',
    state: {
        permanentFilters: Array()
    },
    plugins: [createPersistedState({
        storage: {
          getItem: key => Cookies.get(key),
          setItem: (key, value) => Cookies.set(key, value, { expires: 1}),
          removeItem: key => Cookies.remove(key)
        }
    })],
    mutations: {
        UPDATE_FILTERS(state,payload) {
            let found = false;
            for (var i in state.permanentFilters) {
                if (state.permanentFilters[i].key == payload.key) {
                    state.permanentFilters[i] = payload;
                    found = true;
                    break;
                }
            }
            if(!found){
                state.permanentFilters.push(payload);
            }
        }
    },
    actions: {
        saveFilters ({commit}, payload){
            commit('UPDATE_FILTERS', payload);
        }
    },
    getters: {
        getFiltersByKey: (state) => (key) => {
            for (var i in state.permanentFilters) {
                if (state.permanentFilters[i].key == key) {
                    return state.permanentFilters[i];
                }
            }
            return undefined;
        }
    }
})
