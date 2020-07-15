<template>
    <div class="container justify-content-center my-5">
        <h1 class="text-center">Editing contact id #{{id}}</h1>
        <div class='col-12 size-form mt-5'>
            <form>
                <h4>General  info</h4>
                <div class="form-group">
                    <label>First Name</label>
                    <input type="text" v-model="contact.first_name" class="form-control" placeholder="Enter first name" required>
                </div>
                <div class="form-group">
                    <label>Last Name</label>
                    <input type="text" v-model="contact.last_name" class="form-control" placeholder="Enter last name" required>
                </div>
                <div class="form-group">
                    <label>Email address</label>
                    <input type="email" v-model="contact.email" class="form-control" placeholder="Enter email" required>
                </div>
                <hr>
                <h4>Address info</h4>
                <div class="form-group">
                    <label>City</label>
                    <input type="text" v-model="contact.address_city" class="form-control" placeholder="Enter city">
                </div>
                <div class="form-group">
                    <label>State</label>
                    <input type="text" v-model="contact.address_state" class="form-control" placeholder="Enter state">
                </div>
                <div class="form-group">
                    <label>Address</label>
                    <input type="text" v-model="contact.address" class="form-control" placeholder="Enter address">
                </div>
                <div class="form-group">
                    <label>Number</label>
                    <input type="number" v-model="contact.address_number" class="form-control" placeholder="Enter address number">
                </div>
                <div class="form-group">
                    <label>CEP</label>
                    <input type="text" v-model="contact.address_cep" class="form-control" placeholder="Enter CEP">
                </div>
                <div class="form-group">
                    <label>District</label>
                    <input type="text" v-model="contact.address_district" class="form-control" placeholder="Enter District">
                </div>
                <h4>Phones info</h4>
                <div class="form-group" v-for="(phone, index) in phones" v-bind:key="index">
                    <label>Number</label>
                    <div class="row">
                        <div class="col-8">
                            <input type="hidden" v-model="phone.id">
                            <input type="number" class="form-control" v-model="phone.number" placeholder="Enter Phone Number" required>
                        </div>
                        <div class="col-4">
                            <button v-if="index === Object.keys(phones).length - 1" type="button" class="btn btn-success" @click="addPhone()">Add</button>
                            <button type="button" class="btn btn-danger" @click="removePhone(index)">Del</button>
                        </div>
                    </div>
                </div>

                <button type="button" class="btn btn-primary" @click="updateAgenda()">
                    Change
                    <font-awesome-icon v-if="loading" icon="spinner" spin />
                </button>
                <button type="button" class="btn btn-secondary" @click="back()">Back</button>

                <p class="text-center" v-if="error"> {{ error_msg }} </p>

            </form>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            id: -1,
            contact: [],
            phones: [
                { number: 0 },
            ],
            loading: false,
            error: false,
            error_msg: ''
        }
    },
    methods: {
        back() {
            this.$router.push('/contacts');
        },
        addPhone() {
            this.phones.push({
                number: 0
            });
        },
        removePhone(index) {
            if(Object.keys(this.phones).length === 1)
                return false;

            this.phones.splice(index, 1);
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
                this.contact = response.data.data.contact[0];

                if(Object.keys(response.data.data.phones).length > 0)
                    this.phones = response.data.data.phones;
            })
            .catch(error => {
                console.log(error);
                this.$router.push('/contacts');
            });
        },
        updateAgenda() {
            if(this.loading === false){
                this.loading = true;

                let json = this.contact;
                json.phones = this.phones;

                this.$http({
                    url: this.api_url + 'agenda',
                    method: 'PUT',
                    data: json,
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': 'Bearer ' + localStorage.getItem('user_token')
                    }
                })
                .then(response => {
                    console.log(response);
                    this.$router.push('/contacts');
                })
                .catch(error => {
                    console.log(error.message);
                    this.error = true;
                    this.error_msg = error.response.data.message;
                })
                .finally(() =>{
                    this.loading = false;
                });
            }

        }
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