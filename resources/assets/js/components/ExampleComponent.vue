<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card card-default">
          <div class="card-header">Example Component</div>
          <div class="card-body">
            I'm an example component.<br>
            <span style="color:red;">{{ test }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    data: () => ({
    }),
    
    props: {
      test: String,
    },

    mounted() {
      console.log('Component mounted.')
    },
    
  }
</script>
