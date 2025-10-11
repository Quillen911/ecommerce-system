'use client'
import { useMainData } from '@/hooks/useMainQuery'

export interface CampaignBannerProps {
    className?: string
}

export default function CampaignBanner({ className }: CampaignBannerProps) {
    const { data: mainData, isLoading, error } = useMainData()
    
    if (error) {
        return null
    }
    
    if (isLoading) return null
    
    const campaigns = mainData?.campaigns || []
    
    if (campaigns.length === 0) {
        return null
    }
    console.log(mainData)
    return (
        <div className="bg-black py-2">
            <div className="max-w-10xl mx-auto px-4">
                <div className="relative overflow-hidden">
                    <div className="flex animate-marquee">
                        {[...campaigns, ...campaigns].map((campaign, index) => (
                        <div
                            key={`${campaign.name}-${index}`}
                            className="flex-shrink-0 mx-8 flex items-center space-x-3"
                        >
                            <span className="text-white font-medium text-sm opacity-80">
                            {campaign.description}
                            </span>
                        </div>
                        ))}
                        {[...campaigns, ...campaigns].map((campaign, index) => (
                        <div
                            key={`${campaign.name}-${index}`}
                            className="flex-shrink-0 mx-8 flex items-center space-x-3"
                        >
                            <span className="text-white font-medium text-sm opacity-80">
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