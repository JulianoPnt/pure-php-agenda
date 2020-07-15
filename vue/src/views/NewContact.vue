<template>
    <div class="container justify-content-center">
        <h1 class="text-center">Creating new contact</h1>
        <div class='col-12 size-form mt-5'>
            <form>
                <div class="form-group">
                    <label>First Name</label>
                    <input type="email" class="form-control" placeholder="Enter first name" required>
                </div>
                <div class="form-group">
                    <label>Last Name</label>
                    <input type="email" class="form-control" placeholder="Enter last name" required>
                </div>
                <div class="form-group">
                    <label>Email address</label>
                    <input type="email" class="form-control" placeholder="Enter email" required>
                </div>

                <button class="btn btn-primary">Create</button>
                <button class="btn btn-secondary" @click="back()">Back</button>
            </form>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
        }
    },
    methods: {
        back() {
            this.$router.push('/contacts');
        }
    },
    created() {
        this.checkToken();
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