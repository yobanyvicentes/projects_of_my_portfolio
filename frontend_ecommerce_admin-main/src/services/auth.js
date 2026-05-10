import { axiosInstance } from "../helpers/axios-config";

export const postLogin = (data) => {
    return axiosInstance.post('auth/login', data, {
        headers:{
            'Content-type' : 'application/json'
        }
    })
}
