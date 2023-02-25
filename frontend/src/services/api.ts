/* eslint-disable quote-props */
import axios from 'axios'
export namespace ApiService {
  export const api = axios.create({
    baseURL: import.meta.env.VITE_CHALLENGE_URL,
    headers: {
      Authorization: `Bearer ${localStorage.getItem('token')}`,
      'Content-Type': 'application/json',
    },
  })

  export const fetchbById = async (url: string, id: string) => {
    const response = await api.get(`${url}/${id}`)
    return response.data
  }

  export const fetchAll = async (url: string) => {
    const response = await api.get(url)
    return response.data
  }

  export const insert = async (url: string, data: any) => {
    const response = await api.post(url, data)
    return response.data
  }

  export const update = async (url: string, id: string, data: any) => {
    const response = await api.patch(`${url}/${id}`, data)
    return response.data
  }

  export const deleteById = async (url: string, id: number) => {
    const response = await api.delete(`${url}/${id}`)
    return response.data
  }
}

// update non consitenct naming
export enum API_URL {
  BOOKINGS = '/bookings',
  DIRECTORS = '/directors',
  DRIVING_SHCOOLS = '/driving-schools',
  MONITORS = '/monitors',
  PACKAGES = '/packages',
  PAYMENTS = '/payments',
  STUDENTS = '/students',
}
