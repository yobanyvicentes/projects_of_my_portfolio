import { axiosInstance } from "../helpers/axios-config";

export const getRoles = () => {
    return axiosInstance.get('role', {
        headers:{
            'Content-type' : 'application/json'
        }
    });
}
export const postRole = (data) => {
    return axiosInstance.post('role', data, {
        headers:{
            'Content-type' : 'application/json'
        }
    })
}
export const getRoleById = (roleId) => {
    return axiosInstance.get(`role/${roleId}`, {
        headers:{
            'Content-type' : 'application/json'
        }
    })
}
export const putRole = (roleId, data) => {
    return axiosInstance.put(`role/edit/${roleId}`,data,  {
        headers:{
            'Content-type' : 'application/json'
        }
    })
}
export const deleteRoleById = (roleId) => {
    return axiosInstance.delete(`role/delete/${roleId}`, {
        headers:{
            'Content-type' : 'application/json'
        }
    })
}
