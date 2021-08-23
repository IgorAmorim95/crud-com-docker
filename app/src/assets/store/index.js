import showDeveloperModule from "./modules/showDeveloperModule";

export default {
    state: {
        developer: undefined
    },
    mutations: {
        increment (state) {
            state.count++
        }
    },
    modules: {
        showDeveloper: showDeveloperModule,
    }
}