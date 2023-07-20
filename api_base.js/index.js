import axios from 'axios'

const api = axios.create({
  headers: {
    common: {
      accept: 'application/json'
    }
  }
})

api.defaults.withCredentials = true
api.defaults.baseURL = 'http://localhost'

export default api
