<template>
    <div class="color-bg">
        <nav class="navbar navbar-dark bg-dark">
            <div class="container my-2">
                <div></div>
                <form class="form-inline my-2 my-lg-0">
                    <button class="btn btn-outline-primary my-2 my-sm-0" @click="logout()">Logout</button>
                </form>
            </div>
        </nav>
        <div class="body">
            <div class="outer-container">
                <router-link :to="{ path: '/newcontact'}">
                    <button class="btn btn-primary" >New contact</button>
                </router-link>

                <h1>Contact List</h1>
                <div class="text-center text-capitalize">
                    <small> {{ pagination.perpage }} per page </small> 
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in items" v-bind:key="item.id" class="side-tab">
                            <td class="name">{{item.first_name}}</td>
                            <td class="name">{{item.last_name}}</td>
                            <td class="clicks">{{item.email}}</td>
                            <td class="delete">
                                <button class="edit-btn">
                                    <router-link :to="{ path: '/contact/'+ item.id}">
                                        <font-awesome-icon class="edit-icon" icon="pen" />
                                    </router-link>

                                </button>
                                <button class="delete-btn">
                                    <font-awesome-icon class="delete-icon" @click="deleteContact(item.id)" icon="trash" />
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <nav class="d-flex justify-content-center mt-5">
                    <ul class="pagination ">
                        <li class="page-item" v-if="pagination.prev_page != -1">
                            <a class="page-link" href="#" @click="setPage(pagination.prev_page)" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                                <span class="sr-only">Previous</span>
                            </a>
                        </li>
                        <li class="page-item" v-for="(p, index) in pagination.total_pages" v-bind:key="index">
                            <a class="page-link" v-bind:class="checkPage(p)" href="#" @click="setPage(p)">
                                {{p}}
                            </a>
                        </li>
                        <li class="page-item" v-if="pagination.next_page != -1">
                            <a class="page-link" href="#" @click="setPage(pagination.next_page)" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                                <span class="sr-only">Next</span>
                            </a>
                        </li>
                    </ul>
                </nav>

            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            loading: false,
            pagination: {
                page: 1,
                perpage: 5, //Default (can be changed)
                total_pages: 1,
                next_page: -1,
                prev_page: -1,
            },
            items: [] 
        }
    },
    methods: {
        getAgenda() {
            this.$http({
                url: this.api_url + 'agendaPag/' + this.pagination.page + '/' + this.pagination.perpage,
                method: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('user_token')
                }
            })
            .then(response => {
                console.log(response);
                this.items = response.data.data.contacts;
                this.pagination = response.data.data.pagination;

                console.log(this.items);
            })
            .catch(error => {
                console.log(error);
            });
        },
        logout() {
            localStorage.removeItem('user_token');
            localStorage.removeItem('expires_at');
            this.$router.push('/login');
        },
        deleteContact(id) {
            return this.$http({
                url: this.api_url + 'agenda/' + id,
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + localStorage.getItem('user_token')
                }
            })
            .then(response => {
                console.log(response);
                this.getAgenda();
            })
            .catch(error => {
                console.log(error);
            });
        },
        setPage(page) {
            this.pagination.page = page;
            this.getAgenda();
        },
        checkPage(page){
            if(this.pagination.page == page)
                return 'text-black-50';
        }
    },
    created() {
        this.checkToken();
        this.getAgenda();
    }
}
</script>

<style lang="scss" scoped>
@import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap');

    $--body-background: #e6e6e6b9;
    $--body-text: #ffffff;
    $--container-background: #273349;
    $--table-background: #1c223b;
    $--table-background-hover: #151b31;
    $--table-text: #e2e2e2;
    $--side-tab: rgb(11, 234, 241);

    $--delete-background: #242c4c;
    $--delete-color: #909090;
    $--delete-hover: #ff0000;

    $--edit-background: #242c4c;
    $--edit-color: #909090;
    $--edit-hover: rgb(72, 255, 0);
    


.body {
    animation: fadeInPage ease 1.5s; /* fade page in */
    animation-iteration-count: 1; /* fade page in */
    animation-fill-mode: forwards; /* fade page in */    
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100vh;
    background-color: $--body-background;
    color: $--body-text;
    font-family: 'Open Sans', sans-serif;
    margin: 0;
}
.color-bg {
    background-color: $--body-background;
}
h1 {
    text-align: center;
    margin: -2.5rem 0 0.625rem 0;
}
/* END CSS RESET + COMMON STYLES */


/* START OUTER CONTAINER STYLES */
.outer-container {
    background-color: $--container-background;
    border-radius: 0.625rem;
    box-shadow: 0 5px 10px rgba(12,16,31,0.4);
    padding: 6.25rem;
    margin: auto;
    width: 90%;
    max-width: 62.5rem;
}

/* Start Table Styles */
table {
    color: --table-text;
    font-size: 0.875rem;
    padding: 0.625rem;
    width: 100%;
}
table th {
    font-size: 1rem;
}
table th, table td {
    padding: 0.9375rem;
    text-align: left;
}
table tbody tr {
    background-color: $--table-background;
}
table tbody tr:hover {
    background-color: $--table-background-hover;
    box-shadow: 0 3px 5px rgba(0,0,0,0.2);
}
table tbody tr td:first-of-type {
    border-top-left-radius: 0.3125rem;
    border-bottom-left-radius: 0.3125rem;
}
table tbody tr td:last-of-type {
    border-top-right-radius: 0.3125rem;
    border-bottom-right-radius: 0.3125rem;
    text-align: center;
}
table tbody tr.side-tab td:first-of-type {
    border-left: 0.3125rem solid $--side-tab;
}
table tbody tr td .fa-circle {
    transform: scale(0.7);
}
table tbody tr.side-tab td .fa-circle {
    color: $--side-tab;
}

.delete-btn {
    cursor: pointer;
    background-color: $--delete-background;
    background-color: transparent;
    border: 0;
    border-radius: 0.125rem;
    color: --delete-color;
    font-size: 1.1rem;
    opacity: 0.2;
    padding: 0.3125rem 0.625rem;
}
.delete-btn:hover {
    color: --delete-hover;
}
.delete-btn:focus {
  outline: 0;
}

.delete-icon {
    color: $--delete-color;
    &:hover {
        color: $--delete-hover;
    }
}
table tbody tr:hover .delete-btn {
    opacity: 1;
}

.edit-btn {
    cursor: pointer;
    background-color: $--edit-background;
    background-color: transparent;
    border: 0;
    border-radius: 0.125rem;
    color: --edit-color;
    font-size: 1.1rem;
    opacity: 0.2;
    padding: 0.3125rem 0.625rem;
}
.edit-btn:hover {
    color: --edit-hover;
}
.edit-btn:focus {
  outline: 0;
}

.edit-icon {
    color: $--edit-color;
    &:hover {
        color: $--edit-hover;
    }
}
table tbody tr:hover .edit-btn {
    opacity: 1;
}


@keyframes fadeInPage {
    0% {
        opacity: 0;
    }
    100% {
        opacity: 1;
    }
}

@media(max-width: 768px) {
    .outer-container {
        padding: 1.25rem;
    }
    h1 {
        margin: 0;
    }
}
@media(max-width: 668px) {
    table th:nth-of-type(3), table td:nth-of-type(3) {
        display: none;
    }
}
</style>