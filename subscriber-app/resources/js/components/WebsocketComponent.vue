<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Notifications</div>

                    <div class="card-body" v-bind:key="87789">
                        
                        <p>Topic: {{topic}}</p>
                        <p>New Messsage: {{message}}</p>
                       
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {

        data(){
            return {
                topic: "",
                message: ""
            }
        },
  
        mounted() {

           window.Echo.channel('publish')
                .listen('MessagePublishedForTopic', (data) => {               
                    this.message = data.message;
                    this.topic = data.topic;
                });
        }
    }
</script>
