/** @type {import('next').NextConfig} */
const nextConfig = {
  async rewrites() {
    return [
      {
        source: '/api/:path*',
        destination: 'https://bookingsystem-krms.onrender.com/api/:path*',
      },
    ];
  },
};

export default nextConfig;