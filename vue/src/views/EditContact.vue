<template>
    <div class="container justify-content-center">
        <h1 class="text-center">Editing contact id #{{id}}</h1>
        <div class='col-12 size-form mt-5'>
            <form>
                <div class="form-group">
                    <label>First Name</label>
                    <input type="email" class="form-control" v-model="data.contact[0].first_name" placeholder="Enter first name" required>
                </div>
                <div class="form-group">
                    <label>Last Name</label>
                    <input type="email" class="form-control" v-model="data.contact[0].last_name" placeholder="Enter last name" required>
                </div>
                <div class="form-group">
                    <label>Email address</label>
                    <input type="email" class="form-control" v-model="data.contact[0].email" placeholder="Enter email" required>
                </div>

                <button class="btn btn-primary">Change</button>
                <button class="btn btn-secondary" @click="back()">Back</button>
            </form>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            id: -1,
            data: [],
            phones: []
        }
    },
    methods: {
        checkToken() {
            return this.$http({
                url: this.api_url + 'auth/checktoken',
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + localStorage.getItem('user_token')
                },
            })
            .then(response => {
                console.log(response);
            })
            .catch(error => {
                console.log(error);
                localStorage.removeItem('user_token');
                localStorage.removeItem('expires_at');
                this.$router.push('/login');
            });
        },
        back() {
            this.$router.push('/contacts');
        },
        getAgenda(id) {
            return this.$http({
                url: this.api_url + 'agenda/' + id,
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + localStorage.getItem('user_token')
                }
            })
            .then(response => {
                console.log(response);
                this.data = response.data.data;
                console.log(this.data);
            })
            .catch(error => {
                console.log(error);
            });
        },
    },
    created() {
        this.checkToken();

        this.id = this.$route.params.id;
        if(this.id === -1)
            this.$router.push('/contacts');
        
        this.getAgenda(this.id);

    }
}
</script>

<style lang="scss" scoped>
.size-form {
    max-width: 500px;
    margin-right: auto;
    margin-left: auto;
}
</style>