export default {
    state: {
        chats: [
            {
                id: 1, 
                title: 'frontenddfffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffff', 
                showSubChat: false,
                thereIsSsubChats: true,
                showCross: false,
                subChats: [
                    {id: 1, showCross: false, title: 'регистрация'},
                    {id: 2, showCross: false, title: 'авторизация'},
                    {id: 3, showCross: false, title: 'интерфейс чатов'},
                ]
            },
            {
                id: 2, 
                title: 'backend', 
                showSubChat: false,
                thereIsSsubChats: true,
                showCross: false,
                subChats: [
                    {id: 1, showCross: false, title: 'api'},
                    {id: 2, showCross: false, title: 'почтовый сервер'},
                    {id: 3, showCross: false, title: 'установщик'},
                    {id: 4, showCross: false, title: 'установщикdddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd'},
                    {id: 5, showCross: false, title: 'установщик'},
                    {id: 6, showCross: false, title: 'установщик'},
                    {id: 7, showCross: false, title: 'установщик'},
                    {id: 8, showCross: false, title: 'установщик'},
                    {id: 9, showCross: false, title: 'установщик'},
                    {id: 10, showCross: false, title: 'установщик'},
                    {id: 11, showCross: false, title: 'установщик'},
                    {id: 12, showCross: false, title: 'установщик'},
                    {id: 13, showCross: false, title: 'установщик'},
                    {id: 14, showCross: false, title: 'установщик'},
                ]
            },
            {
                id: 3, 
                title: 'frontend', 
                showSubChat: false,
                thereIsSsubChats: false,
                showCross: false,
            }
        ],
        show: false,
        showCross: false,
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
        changeShowCross(ctx, id) {
            ctx.commit('updateShowCross', id)
        },
        changeShowSubCross(ctx, allId) {
            ctx.commit('updateShowSubCross', allId)
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
        updateShowCross(state, id) {
            state.chats[id].showCross = !(state.chats[id].showCross)
        },
        updateShowSubCross(state, allId) {
            state.chats[allId.idChat].subChats[allId.idSubChat].showCross = (!state.chats[allId.idChat].subChats[allId.idSubChat].showCross)
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