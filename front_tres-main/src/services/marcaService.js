import { axiosInstance } from "../helpers/axios-config";

export const getMarcas = () => {
    return axiosInstance.get('marca', {
        headers:{
            'Content-type' : 'application/json'
        }
    })
}

export const getMarcaById = (marcaId) => {
    return axiosInstance.get(`marca/${marcaId}`, {
        headers:{
            'Content-type' : 'application/json'
        }
    })
}

export const postMarcas = (data) => {
    return axiosInstance.post('marca', data, {
        headers:{
            'Content-type' : 'application/json'
        }
    })
}

export const putMarcas = (marcaId, data) => {
    return axiosInstance.put(`marca/${marcaId}`,data,  {
        headers:{
            'Content-type' : 'application/json'
        }
    })
}
