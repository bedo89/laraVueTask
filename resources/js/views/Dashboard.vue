<template>
    <div class="container-fluid">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <router-link to="/home">Dashboard</router-link>
            </li>
            <li class="breadcrumb-item active">Overview</li>
        </ol>

        <div class="row">

            <div class="col-xl-6 col-sm-12 mb-6">
                <div class="card text-white bg-warning o-hidden h-100">
                    <div class="card-body">
                        <div class="card-body-icon">
                            <i class="fas fa-fw fa-list"></i>
                        </div>
                        <div class="mr-5">Products !</div>
                    </div>
                    <router-link to="/home/products" class="card-footer text-white clearfix small z-1">
                        <span class="float-left">View Details</span>
                        <span class="float-right">
                            <i class="fas fa-angle-right"></i>
                        </span>
                    </router-link>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import * as user from '../services/user_service';
    export default {
        methods: {
            userScope: async function() {
                try {
                    const response = await user.userScope();
                    console.log(response);
                } catch (error) {
                    console.log(' '+error, error.response.statu);
                }
            }
        }
    }
</script>
