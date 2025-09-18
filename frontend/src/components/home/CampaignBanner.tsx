'use client'
import { useMainData } from '@/hooks/useMainQuery'

export default function CampaignBanner() {
    const { data: mainData, isLoading, error } = useMainData()
    
    if (error) {
        return null
    }
    
    if (isLoading) return null
    
    const campaigns = mainData?.campaigns || []
    
    if (campaigns.length === 0) {
        return null
    }
    
    return (
        <div className="bg-black py-4">
            <div className="max-w-10xl mx-auto px-4">
                <div className="relative overflow-hidden">
                    <div className="flex animate-marquee">
                        {campaigns.map((campaign) => (
                            <div key={campaign.id} className="flex-shrink-0 mx-8 flex items-center space-x-3">
                                <div className="w-2 h-2 bg-red-500 rounded-full animate-pulse"></div>
                                <span className="text-white font-medium text-sm">
                                   {campaign.store_name} Mağazasında
                                </span>

                                <span className="text-white font-medium text-sm opacity-80">
                                    {campaign.name}
                                </span>
                                
                            </div>
                        ))}
                        {campaigns.map((campaign) => (
                            <div key={`copy-${campaign.id}`} className="flex-shrink-0 mx-8 flex items-center space-x-3">
                                <div className="w-2 h-2 bg-red-500 rounded-full animate-pulse"></div>
                                <span className="text-white font-medium text-sm">
                                    {campaign.store_name} Mağazasında
                                </span>
                                <span className="text-white font-medium text-sm opacity-80">
                                    {campaign.name}
                                </span>
                            </div>
                        ))}
                    </div>
                </div>
            </div>
        </div>
    )
}