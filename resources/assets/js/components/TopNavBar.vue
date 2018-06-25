<template>
    <header class="app-header navbar">
        <button class="navbar-toggler mobile-sidebar-toggler d-lg-none" type="button">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="#"></a>
        <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button">
            <span class="navbar-toggler-icon"></span>
        </button>
        <ul class="nav navbar-nav d-md-down-none ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                    <img class="img-avatar" src="images/image-avatar.png">
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <form method="post" :action="logoutPath" ref="logout">
                        <input type="hidden" name="_token" :value="csrfToken">
                    </form>
                    <a class="dropdown-item" href="#" @click="doLogout">
                        <i class="fa fa-lock"></i>Logout
                    </a>
                </div>
            </li>
        </ul>
    </header>
</template>
<script>
    export default {

    	methods: {
    		doLogout() {
    			this.$refs.logout.submit();
            }
        },

        computed: {
    		logoutPath() {
    			let path = window.location.pathname;
    			return `${path.substr(0, path.indexOf('/', 1))}/logout`;
            },

    		csrfToken() {
    			return window.token;
            }
        }

    }
</script>