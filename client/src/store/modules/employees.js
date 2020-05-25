export default {
    state: {
        emploees: [
            {id: 1, userId: 2, dialogId: 1, firstName: 'Фёдор', surname: 'Фёдоров', avatar: 'https://placeimg.com/40/40/people/6'},
            {id: 2, userId: 3, dialogId: 2, firstName: 'Вадим', surname: 'Вадимов', avatar: 'https://placeimg.com/40/40/people/4'},
            {id: 3, userId: 4, dialogId: 3, firstName: 'Илья', surname: 'Ильин', avatar: 'https://placeimg.com/40/40/people/3'},
            {id: 4, userId: 5, dialogId: 1, firstName: 'Пётр', surname: 'Петров', avatar: 'https://placeimg.com/40/40/people/2'},
            {id: 5, userId: 6, dialogId: 2, firstName: 'Карл', surname: 'Карлов', avatar: 'https://placeimg.com/40/40/people/1'},
        ],
    },
    getters: {
        employees: state => state.emploees,
    },
    actions: {

    },
    mutations: {

    }
}