<template>
  <div class="login-container">
    <h1>Login</h1>
    <form method="post" action="/login">
      <div class="form-group">
        <label for="email">Email</label>
        <input
          type="text"
          id="email"
          name="email"
          placeholder="Enter your email"
          required
          aria-label="Email"
        />
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input
          type="password"
          id="password"
          name="password"
          placeholder="Enter your password"
          required
          aria-label="Password"
        />
      </div>
      <input type="hidden" name="_csrf_token" value="{{ csrf_token('authenticate') }}">
      <div v-if="error" class="error-message">
        {{ error }}
      </div>
      <button type="submit">Login</button>
    </form>
  </div>
</template>

<script>
export default {
  name: "Login",

  props: {
    csrfToken: String
  },

  data() {
    return {
      error: null
    }
  },
  mounted() {
    // Obtenha o token CSRF do servidor
    fetch('/login')
      .then(response => response.json())
      .then(data => {
        this.csrfToken = data.csrf_token;
      });
  },
};
</script>

<style scoped>
.login-container {
  max-width: 400px;
  margin: 0 auto;
  padding: 20px;
  border: 1px solid #ccc;
  border-radius: 8px;
  background-color: #f9f9f9;
}

.form-group {
  margin-bottom: 15px;
}

label {
  display: block;
  margin-bottom: 5px;
}

input {
  width: 100%;
  padding: 8px;
  box-sizing: border-box;
}

button {
  width: 100%;
  padding: 10px;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

button:hover {
  background-color: #0056b3;
}

.error-message {
  color: red;
  margin-top: 10px;
}
</style>