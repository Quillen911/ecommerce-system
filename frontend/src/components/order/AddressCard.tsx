
import type { UserAddress } from '@/types/userAddress';

type AddressCardProps = {
  title: string;
  address: UserAddress;
};

export function AddressCard({ title, address }: AddressCardProps) {
  const {
    first_name,
    last_name,
    phone,
    address_line_1,
    address_line_2,
    district,
    city,
    postal_code,
    country,
    notes,
  } = address;

  return (
    <section className="rounded-2xl bg-white p-4 shadow-sm">
      <h2 className="text-base font-semibold text-neutral-900">{title}</h2>
      <div className="mt-3 space-y-2 text-sm text-neutral-600">
        <p className="font-medium text-neutral-800">
          {first_name} {last_name}
        </p>
        <p>{phone}</p>
        <p>{address_line_1}</p>
        {address_line_2 ? <p>{address_line_2}</p> : null}
        <p>
          {district} / {city}
        </p>
        <p>
          {postal_code} {country}
        </p>
        {notes ? <p className="text-neutral-500">Not: {notes}</p> : null}
      </div>
    </section>
  );
}
