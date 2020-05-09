export default {
    state: {
        headerItemArr: [
            {id: 0, title: 'О нас', important: false, visibility: true, path: "#"},
            {id: 1, title: 'Как начать', important: false, visibility: true, path: "#"},
            {id: 2, title: 'На главную', important: false, visibility: false, path: "/"},
            {id: 3, title: 'Авторизоваться', important: true, visibility: true, path: "/authorization"},
            {id: 4, title: 'Зарегестрироваться', important: false, visibility: true, path: "/registration"}
        ]
    },
    actions: {
        changeHeaderItems(ctx, id) {
            if (id === 0) {
                ctx.commit('updateFirstItem')
            }
            else if (id === 1) {
                ctx.commit('updateSecondItem')
            }
            else if (id === 2) {
                ctx.commit('updateThirdItem')
            }
            else if (id === 3) {
                ctx.commit('updateFourthItem')
            }
            else if (id === 4) {
                ctx.commit('updateFifthItem')
            }
        }
    },
    mutations: {
        updateFirstItem(state) {
            state.headerItemArr[0].visibility = false
            state.headerItemArr[0].visibility = true
            state.headerItemArr[0].visibility = true
        },
        updateSecondItem(state) {
            state.headerItemArr[0].visibility = true
            state.headerItemArr[1].visibility = false
            state.headerItemArr[2].visibility = true
        },
        updateThirdItem(state) {
            state.headerItemArr[0].visibility = true
            state.headerItemArr[1].visibility = true
            state.headerItemArr[2].visibility = false
            state.headerItemArr[3].visibility = true
            state.headerItemArr[4].visibility = true
            state.headerItemArr[4].important = false
        },
        updateFourthItem(state) {
            state.headerItemArr[2].visibility = true
            state.headerItemArr[3].visibility = false
            state.headerItemArr[4].visibility = true
            state.headerItemArr[4].important = true
        },
        updateFifthItem(state) {
            state.headerItemArr[2].visibility = true
            state.headerItemArr[3].visibility = true
            state.headerItemArr[4].visibility = false
            state.headerItemArr[4].important = false
        },
    },
    getters: {
        headerItemArr(state) {
            return state.headerItemArr
        }
    }
}