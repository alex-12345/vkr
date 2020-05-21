export default {
    state: {
        chats: [
            {
                id: 1, 
                title: 'frontenddfffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffff', 
                showSubChat: false,
                thereIsSsubChats: true,
                showCross: false,
                showSubChatInput: false,
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
                showSubChatInput: false,
                subChats: [
                    {id: 1, showCross: false, title: 'api'},
                    {id: 2, showCross: false, title: 'почтовый серверhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh'},
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
                showSubChatInput: false,
                subChats: []
            }
        ],
        show: false,
        showCross: false,
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
        changeShowCross(ctx, id) {
            ctx.commit('updateShowCross', id)
        },
        changeShowSubCross(ctx, allId) {
            ctx.commit('updateShowSubCross', allId)
        },
        changeShowChatInput(ctx) {
            ctx.commit('updateShowChatInput')
        },
        changeShowSubChatInput(ctx, id) {
            ctx.commit('updateShowSubChatInput', id)
        },
        addChat(ctx, name) {
            ctx.commit('pushChat', name)
        },
        addSubChat(ctx, newSubChat) {
            ctx.commit('pushSubChat', newSubChat)
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
        updateShowCross(state, id) {
            state.chats[id].showCross = !(state.chats[id].showCross)
        },
        updateShowSubCross(state, allId) {
            for (let j = 0; j < state.chats.length; j++) {
                if (state.chats[j].thereIsSsubChats === true) {
                    for (let i = 0; i < state.chats[j].subChats.length; i++) {
                        state.chats[j].subChats[i].showCross = false
                    }
                }
            }            
            state.chats[allId.idChat].subChats[allId.idSubChat].showCross = (!state.chats[allId.idChat].subChats[allId.idSubChat].showCross)
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
                showCross: false,
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
                showCross: false,
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