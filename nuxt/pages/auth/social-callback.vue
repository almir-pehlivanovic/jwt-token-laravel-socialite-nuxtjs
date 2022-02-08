<template>
    <div class="flex justify-center items-center h-screen">
        <button type="button" disabled class="flex py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-500">
            <span class="mr-4 animate-spin block border-2 border-white h-5 rounded-full w-5" viewBox="0 0 24 24">
                <!-- ... -->
            </span>
            Processing...
        </button>
    </div>
</template>

<script>
export default {
    data(){
        return{
            token: this.$route.query.token ? this.$route.query.token : null
        }
    },
    mounted(){
        this.$auth.loginWith('local', { data: {
            token: this.token
        }}).catch((e) => {
            console.log("Your token is not valid. Please try again!");
        })
    },
}
</script>
<style scoped>
  span {
    border-left-color: transparent;
    border-right-color: transparent;
  }
</style>
