export default {
    state: {
        dropDownArr: [
            {id: 1, title: 'Выйти', function: 'logout'}
        ],
        show: false
    },
    getters: {
        showMenuSidebar: state => state.show,
        dropDownArr: state => state.dropDownArr,
    },
    actions: {
        changeShow(ctx, value) {
            ctx.commit('updateShow', value)
        },

    },
    mutations: {
        updateShow(state, value) {
            state.show = value
        }
    }
}