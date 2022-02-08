<template>
    <div>
         <div v-if="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Your token appeared to be invalid. Please try again.</strong>
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
            </span>
        </div>
        <div class="min-h-full flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-md w-full space-y-8">
                <div>
                    <img class="mx-auto h-12 w-auto" src="https://tailwindui.com/img/logos/workflow-mark-indigo-600.svg" alt="Workflow">
                    <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">Register new account</h2>
                </div>
                <form @submit.prevent="register" class="mt-8 space-y-6" action="#" method="POST">
                    <div class="rounded-md shadow-sm -space-y-px">
                        <div>
                            <label for="name" class="sr-only">Name</label>
                            <input id="name" v-model="form.name" type="text" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Name">
                        </div>
                        <div>
                            <label for="email-address" class="sr-only">Email address</label>
                            <input id="email-address" v-model="form.email" type="email" autocomplete="email" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900  focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Email address">
                        </div>
                        <div>
                            <label for="password" class="sr-only">Password</label>
                            <input id="password" v-model="form.password" type="password" autocomplete="current-password" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Password">
                        </div>
                    </div>
                    <div>
                        <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Register
                        </button>
                    </div>
                </form>
                <SocialLogin />
            </div>
        </div>
    </div>
</template>

<script>

import SocialLogin from '../../components/SocialLogin.vue';

export default {
    layout: 'base',
    auth: 'guest',
    components: {
        SocialLogin
    },
    data(){
        return{
            form: {
                name: '',
                email: '',
                password: '',
            },
            error: this.$route.query.error,
        }
    },
    methods: {
        async register(){
            await this.$axios.post('/auth/register',{
            ...this.form,
            password_confirmation: this.form.password,
            });

           try {
            let response = await this.$auth.loginWith('local', { data: this.form })
                console.log(response)
            } catch (err) {
                console.log(err)
            }
        }
    }
}
</script>