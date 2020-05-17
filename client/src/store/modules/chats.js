export default {
    state: {
        chats: [
            {id: 1, title: 'frontend', showSubChat: false,
                subChats: [
                    {id: 1, title: 'регистрация'},
                    {id: 2, title: 'авторизация'},
                    {id: 3, title: 'интерфейс чатов'},
                ]
            },
            {id: 2, title: 'backend', showSubChat: false,
                subChats: [
                    {id: 1, title: 'api'},
                    {id: 2, title: 'почтовый сервер'},
                    {id: 3, title: 'установщик'},
                    /*{id: 4, title: 'установщик'},
                    {id: 5, title: 'установщик'},
                    {id: 6, title: 'установщик'},
                    {id: 7, title: 'установщик'},
                    {id: 8, title: 'установщик'},
                    {id: 9, title: 'установщик'},
                    {id: 10, title: 'установщик'},
                    {id: 11, title: 'установщик'},
                    {id: 12, title: 'установщик'},
                    {id: 13, title: 'установщик'},
                    {id: 14, title: 'установщик'},*/
                ]
            },
        ],
        show: false
    },
    getters: {
        chats: state => state.chats,
        showChats: state => state.show,
    },
    actions: {
        changeShowChats(ctx) {
            ctx.commit('updateShowChats')
        },
        changeShowSubChats(ctx, id) {
            ctx.commit('updateShowSubChats', id)
        },
        addChat(ctx, name) {
            ctx.commit('pushChat', name)
        },
        addSubchats(ctx, id, name) {
            ctx.commit('pushSubchat', id, name)
        },
        delChat(ctx, name) {
            ctx.commit('delChat', name)
        },
        delSubchats(ctx, id, name) {
            ctx.commit('delSubchat', id, name)
        },
    },
    mutations: {
        updateShowChats(state) {
            state.show = !(state.show)
        },
        updateShowSubChats(state, id) {
            state.chats[id].showSubChat = !(state.chats[id].showSubChat)
        },
        pushChat(state, name) {
            state.chats.push({id: state.chats.length + 1, title: name})
        },
        pushSubchat(state, id, name) {
            state.chats[id].subchats.push({id: state.chats[id].subchats.length + 1, title: name})
        },
        delChat(state, name) {
            state.chats.push({id: state.chats.length + 1, title: name})
        },
        delSubchat(state, id, name) {
            state.chats[id].subchats.push({id: state.chats[id].subchats.length + 1, title: name})
        },
    }
}