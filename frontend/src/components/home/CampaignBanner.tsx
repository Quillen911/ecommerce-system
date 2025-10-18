'use client'
import { useMainData } from '@/hooks/useMainQuery'

export interface CampaignBannerProps {
  className?: string
}

export default function CampaignBanner({ className }: CampaignBannerProps) {
  const { data: mainData, isLoading, error } = useMainData()

  if (error) return null
  if (isLoading) return null

  const campaigns = mainData?.campaigns || []
  if (campaigns.length === 0) return null

  return (
    <div className="bg-black py-2 w-full overflow-x-hidden">
      <div className="max-w-10xl mx-auto px-3 sm:px-4">
        <div className="relative overflow-hidden">
          <div className="flex animate-marquee whitespace-nowrap">
            {[...campaigns, ...campaigns].map((campaign, index) => (
              <div
                key={`${campaign.name}-${index}`}
                className="flex-shrink-0 mx-4 sm:mx-8 flex items-center space-x-2 sm:space-x-3"
              >
                <span className="text-white font-medium text-xs sm:text-sm opacity-80 break-words">
                  {campaign.description}
                </span>
              </div>
            ))}
            {[...campaigns, ...campaigns].map((campaign, index) => (
              <div
                key={`${campaign.name}-${index}-dup`}
                className="flex-shrink-0 mx-4 sm:mx-8 flex items-center space-x-2 sm:space-x-3"
              >
                <span className="text-white font-medium text-xs sm:text-sm opacity-80 break-words">
                  {campaign.description}
                </span>
              </div>
            ))}
          </div>
        </div>
      </div>
    </div>
  )
}
