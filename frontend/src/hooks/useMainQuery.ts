import { useQuery } from '@tanstack/react-query'
import { mainApi } from '@/lib/api/mainApi'
import { MainData } from '@/types/main'
import { FilterResponse } from '@/types/search'

export const useMainData = () => {
  return useQuery<MainData>({
    queryKey: ['main-data'],
    queryFn: async () => {
      const response = await mainApi.getMainData()
      return response.data.data
    },
  })
}
