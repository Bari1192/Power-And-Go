import axios from 'axios'

export const http = axios.create({
    baseURL: "http://jsonserver.localhost",
    headers:{
        "Accept": "application/json",
        "Content-Type": "application/json" 
    }
})
