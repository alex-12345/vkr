export default {
    state: {
        messages: [
            {
                id: 1,
                chatId: 1,
                subchatId: null,
                messageArray: [
                    {
                        id: 1, 
                        userId: 1, 
                        firstName: 'Иван', 
                        lastName: 'Иванов', 
                        avatar: 'https://placeimg.com/40/40/people/5', 
                        body: 'Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quia ducimus non tempora officiis eum sint quos quibusdam! Aut, asperiores accusamus officia, quibusdam rem natus aperiam, ipsum facere ea explicabo error?',
                        replay: false
                    },
                    {
                        id: 2, 
                        userId: 1, 
                        firstName: 'Иван', 
                        lastName: 'Иванов', 
                        avatar: 'https://placeimg.com/40/40/people/5', 
                        body: 'Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quia ducimus non tempora officiis eum sint quos quibusdam! Aut, asperiores accusamus officia, quibusdam rem natus aperiam, ipsum facere ea explicabo error?',
                        replay: true
                    },
                    {
                        id: 3, 
                        userId: 1, 
                        firstName: 'Иван', 
                        lastName: 'Иванов', 
                        avatar: 'https://placeimg.com/40/40/people/5', 
                        body: 'Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quia ducimus non tempora officiis eum sint quos quibusdam! Aut, asperiores accusamus officia, quibusdam rem natus aperiam, ipsum facere ea explicabo error?',
                        replay: true
                    },
                    {
                        id: 4, 
                        userId: 2, 
                        firstName: 'Фёдор', 
                        lastName: 'Фёдоров', 
                        avatar: 'https://placeimg.com/40/40/people/6', 
                        body: 'Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quia ducimus non tempora officiis eum sint quos quibusdam! Aut, asperiores accusamus officia, quibusdam rem natus aperiam, ipsum facere ea explicabo error?',
                        replay: false
                    },
                    {
                        id: 5, 
                        userId: 2, 
                        firstName: 'Фёдор', 
                        lastName: 'Фёдоров', 
                        avatar: 'https://placeimg.com/40/40/people/6', 
                        body: 'Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quia ducimus non tempora officiis eum sint quos quibusdam! Aut, asperiores accusamus officia, quibusdam rem natus aperiam, ipsum facere ea explicabo error?',
                        replay: true
                    },
                    {
                        id: 6, 
                        userId: 2, 
                        firstName: 'Фёдор', 
                        lastName: 'Фёдоров', 
                        avatar: 'https://placeimg.com/40/40/people/6', 
                        body: 'Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quia ducimus non tempora officiis eum sint quos quibusdam! Aut, asperiores accusamus officia, quibusdam rem natus aperiam, ipsum facere ea explicabo error?',
                        replay: true
                    },
                ]
            },
            {
                id: 2,
                chatId: 2,
                subchatId: null,
                messageArray: [
                    {
                        id: 1, 
                        userId: 1, 
                        firstName: 'Иван', 
                        lastName: 'Иванов', 
                        avatar: 'https://placeimg.com/40/40/people/5', 
                        body: 'Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quia ducimus non tempora officiis eum sint quos quibusdam! Aut, asperiores accusamus officia, quibusdam rem natus aperiam, ipsum facere ea explicabo error?',
                        replay: false
                    },
                    {
                        id: 2, 
                        userId: 2, 
                        firstName: 'Фёдор', 
                        lastName: 'Фёдоров', 
                        avatar: 'https://placeimg.com/40/40/people/6', 
                        body: 'Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quia ducimus non tempora officiis eum sint quos quibusdam! Aut, asperiores accusamus officia, quibusdam rem natus aperiam, ipsum facere ea explicabo error?',
                        replay: false
                    },
                    {
                        id: 3, 
                        userId: 1, 
                        firstName: 'Иван', 
                        lastName: 'Иванов', 
                        avatar: 'https://placeimg.com/40/40/people/5', 
                        body: 'Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quia ducimus non tempora officiis eum sint quos quibusdam! Aut, asperiores accusamus officia, quibusdam rem natus aperiam, ipsum facere ea explicabo error?',
                        replay: false
                    },
                ]
            },
            {
                id: 3,
                chatId: 3,
                subchatId: null,
                messageArray: []
            },
            {
                id: 4,
                chatId: 1,
                subchatId: 1,
                messageArray: [
                    {
                        id: 1, 
                        userId: 1, 
                        firstName: 'Иван', 
                        lastName: 'Иванов', 
                        avatar: 'https://placeimg.com/40/40/people/5', 
                        body: 'Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quia ducimus non tempora officiis eum sint quos quibusdam! Aut, asperiores accusamus officia, quibusdam rem natus aperiam, ipsum facere ea explicabo error?',
                        replay: false
                    },
                    {
                        id: 2, 
                        userId: 2, 
                        firstName: 'Фёдор', 
                        lastName: 'Фёдоров', 
                        avatar: 'https://placeimg.com/40/40/people/6', 
                        body: 'Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quia ducimus non tempora officiis eum sint quos quibusdam! Aut, asperiores accusamus officia, quibusdam rem natus aperiam, ipsum facere ea explicabo error?',
                        replay: false
                    },
                    {
                        id: 3, 
                        userId: 1, 
                        firstName: 'Иван', 
                        lastName: 'Иванов', 
                        avatar: 'https://placeimg.com/40/40/people/5', 
                        body: 'Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quia ducimus non tempora officiis eum sint quos quibusdam! Aut, asperiores accusamus officia, quibusdam rem natus aperiam, ipsum facere ea explicabo error?',
                        replay: false
                    },
                ]
            },
        ],
        selectedChat: undefined,
    },
    getters: {
        messages: state => state.messages,
        selectedChat: state => state.selectedChat,
    },
    actions: {
        addMessages(ctx, body) {
            ctx.commit('updateMessages', body)
        },
        changeSelectedChat(ctx, allId) {
            ctx.commit('updateSelectedChat', allId)
        }
    },
    mutations: {
        updateMessages(state, body) {
            console.log(state.messages, body)
        },
        updateSelectedChat(state, allId) {
            if (allId.chatId === undefined && allId.subChatId === undefined) {
                state.selectedChat = undefined
            }
            else if (allId.chatId != undefined && allId.subChatId === undefined) {
                let array = state.messages.find(item => item.chatId === allId.chatId)
                if (array === undefined) {
                    state.selectedChat = {messageArray: []}
                }
                else {state.selectedChat = array}
            }
            else if (allId.chatId != undefined && allId.subChatId != undefined) {
                let array = state.messages.find(item => item.chatId === allId.chatId && item.subchatId === allId.subChatId)
                if (array === undefined) {
                    state.selectedChat = {messageArray: []}
                }
                else {state.selectedChat = array}
            }
        }
    }
}