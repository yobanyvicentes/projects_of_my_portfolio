import { axiosInstance } from "../helpers/axios-config";

export const getInventarios = () => {
    return axiosInstance.get('inventario', {
        headers:{
            'Content-type' : 'application/json'
        }
    })
}


export const getInventariosById = (inventarioId) => {
    return axiosInstance.get(`inventario/${inventarioId}`, {
        headers:{
            'Content-type' : 'application/json'
        }
    })
}


export const postInventarios = (data) => {
    return axiosInstance.post('inventario', data, {
        headers:{
            'Content-type' : 'application/json'
        }
    })
}

export const putInventarios = (inventarioId, data) => {
    return axiosInstance.put(`inventario/${inventarioId}`,data, {
        headers:{
            'Content-type' : 'application/json'
        }
    })
}
