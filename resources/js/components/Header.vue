<template>
    <nav class="navbar navbar-expand navbar-dark bg-dark static-top">
        <a class="navbar-brand mr-1" href="index.html">{{$store.state.profile.name}}</a>

        <button class="btn btn-link btn-sm text-white order-1 order-sm-0" id="sidebarToggle" href="#">
            <i class="fas fa-bars"></i>
        </button>

        <ul class="navbar-nav ml-auto ml-md-6">
            <li class="nav-item dropdown no-arrow">
                <a
                    class="nav-link dropdown-toggle"
                    href="#"
                    id="userDropdown"
                    role="button"
                    data-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false"
                >
                    <i class="fas fa-user-circle fa-fw"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                    <button class="dropdown-item" v-on:click="logout">Logout</button>
                </div>
            </li>
        </ul>
    </nav>
</template>

<script>
    import * as auth from '../services/auth_service';
    export default {
        methods: {
            logout: async function() {
                auth.logout();
                this.$router.push('/login');
            }
        }
    }
</script>
