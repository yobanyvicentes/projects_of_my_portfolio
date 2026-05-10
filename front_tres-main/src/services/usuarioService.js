import { axiosInstance } from "../helpers/axios-config";

export const getUsuarios = () => {
    return axiosInstance.get('usuario', {
        headers:{
            'Content-type' : 'application/json'
        }
    });
};

export const getUsuarioById = (usuarioId) => {
    return axiosInstance.get(`usuario/${usuarioId}`, {
        headers:{
            'Content-type' : 'application/json'
        }
    })
}

export const postUsuarios = (data) => {
    return axiosInstance.post('usuario', data, {
        headers:{
            'Content-type' : 'application/json'
        }
    })
}

export const putUsuarios = (usuarioId, data) => {
    return axiosInstance.put(`usuario/${usuarioId}`,data,  {
        headers:{
            'Content-type' : 'application/json'
        }
    })
}
