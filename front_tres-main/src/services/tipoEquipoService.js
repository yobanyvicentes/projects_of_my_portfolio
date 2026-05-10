import { axiosInstance } from "../helpers/axios-config";

export const getTipoEquipos = () => {
    return axiosInstance.get('tipo-equipo', {
        headers:{
            'Content-type' : 'application/json'
        }
    })
}

export const getTipoEquipoById = (tipoEquipoId) => {
    return axiosInstance.get(`tipo-equipo/${tipoEquipoId}`, {
        headers:{
            'Content-type' : 'application/json'
        }
    })
}

export const postTipoEquipos = (data) => {
    return axiosInstance.post('tipo-equipo', data, {
        headers:{
            'Content-type' : 'application/json'
        }
    })
}

export const putTipoEquipos = (tipoId, data) => {
    return axiosInstance.put(`tipo-equipo/${tipoId}`,data,  {
        headers:{
            'Content-type' : 'application/json'
        }
    })
}
