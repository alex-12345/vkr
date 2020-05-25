export default {
    state: {
        chats: [
            {
                id: 1, 
                title: 'веб-чатfffffffffffffffffffffffffffffffffffffff', 
                showSubChat: false,
                thereIsSsubChats: true,
                showSubChatInput: false,
                subChats: [
                    {id: 1, title: 'регистрация'},
                    {id: 2, title: 'авторизация'},
                    {id: 3, title: 'интерфейс чатов'},
                ]
            },
            {
                id: 2, 
                title: 'backend', 
                showSubChat: false,
                thereIsSsubChats: true,
                showSubChatInput: false,
                subChats: [
                    {id: 1, title: 'api'},
                    {id: 2, title: 'почтовый серверhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh'},
                    {id: 3, title: 'установщик'},
                    {id: 4, title: 'установщикdddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd'},
                    {id: 5, title: 'установщик'},
                    {id: 6, title: 'установщик'},
                    {id: 7, title: 'установщик'},
                    {id: 8, title: 'установщик'},
                    {id: 9, title: 'установщик'},
                    {id: 10, title: 'установщик'},
                    {id: 11, title: 'установщик'},
                    {id: 12, title: 'установщик'},
                    {id: 13, title: 'установщик'},
                    {id: 14, title: 'установщик'},
                ]
            },
            {
                id: 3, 
                title: 'frontend', 
                showSubChat: false,
                thereIsSsubChats: false,
                showSubChatInput: false,
                subChats: []
            }
        ],
        show: false,
        showChatInput: false,
        selectedChat: undefined,
    },
    getters: {
        chats: state => state.chats,
        showChats: state => state.show,
        showChatInput: state => state.showChatInput,
    },
    actions: {
        changeShowChats(ctx) {
            ctx.commit('updateShowChats')
        },
        changeShowSubChats(ctx, id) {
            ctx.commit('updateShowSubChats', id)
        },
        changeShowChatInput(ctx) {
            ctx.commit('updateShowChatInput')
        },
        changeShowSubChatInput(ctx, id) {
            ctx.commit('updateShowSubChatInput', id)
        },
        addChat(ctx, name) {
            if (name != '') {
                console.log('Я тут 1')
                ctx.commit('pushChat', name)
            }
        },
        addSubChat(ctx, newSubChat) {
            if (newSubChat.name != '') {
                console.log('Я тут 2')
                ctx.commit('pushSubChat', newSubChat)
            }
        },
        delChat(ctx, id) {
            ctx.commit('delChat', id)
        },
        delSubchats(ctx, allId) {
            ctx.commit('delSubchat', allId)
        },
    },
    mutations: {
        updateShowChats(state) {
            state.show = !(state.show)
        },
        updateShowSubChats(state, id) {
            state.chats[id].showSubChat = !(state.chats[id].showSubChat)
        },
        updateShowChatInput(state) {
            state.showChatInput = !(state.showChatInput)
        },
        updateShowSubChatInput(state, id) {
            state.chats[id].showSubChatInput = !(state.chats[id].showSubChatInput)
        },
        pushChat(state, name) {
            state.chats.push({
                id: state.chats.length + 1, 
                title: name, 
                showSubChat: false,
                thereIsSsubChats: false,
                showSubChatInput: false,
                subChats: []
            })
        },
        pushSubChat(state, newSubChat) {
            if (state.chats[newSubChat.id].subChats.length === 0) {
                state.chats[newSubChat.id].thereIsSsubChats = true
            }
            state.chats[newSubChat.id].subChats.push({
                id: state.chats[newSubChat.id].subChats.length + 1,
                title: newSubChat.name,
            })
        },
        delChat(state, id) {
            state.chats.splice(id, 1)
            for (let i = id; i < state.chats.length; i++) {
                state.chats[i].id = state.chats[i].id - 1
            }
            if (state.chats.length === 0) {
                state.show = false
            }
        },
        delSubchat(state, allId) {
            state.chats[allId.idChat].subChats.splice(allId.idSubChat, 1)
            for (let i = allId.idSubChat; i < state.chats[allId.idChat].subChats.length; i++) {
                state.chats[allId.idChat].subChats[i].id = state.chats[allId.idChat].subChats[i].id - 1
            }
            if (state.chats[allId.idChat].subChats.length === 0) {
                state.chats[allId.idChat].thereIsSsubChats = false
            }
        },
    }
}