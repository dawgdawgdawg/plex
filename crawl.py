import requests
from bs4 import BeautifulSoup
from urllib.parse import urlparse, urljoin
import chardet

# Set to store crawled URLs
crawled_urls = set()

# Function to crawl the web and save links to Found.txt
def crawl_web(url):
    try:
        headers = {
            'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
        }

        urls_to_crawl = [url]  # List of URLs to be crawled

        while urls_to_crawl:
            current_url = urls_to_crawl.pop(0)  # Get the first URL from the list
            response = requests.get(current_url, headers=headers)
            response.raise_for_status()
            encoding = chardet.detect(response.content)['encoding']
            soup = BeautifulSoup(response.content.decode('utf-8', 'ignore'), 'html.parser')

            # Check if the URL has already been crawled
            if current_url not in crawled_urls:
                title = soup.title.string if soup.title else "Untitled"
                with open("Found.txt", "a", encoding="utf-8") as file:
                    file.write(f"{current_url} ||| {title}\n")

                # Add the URL to the set of crawled URLs
                crawled_urls.add(current_url)

                for link in soup.find_all('a'):
                    href = link.get('href')
                    if href and href.startswith('http'):
                        parsed_href = urlparse(href)
                        parsed_url = urlparse(current_url)

                        # Check if the href belongs to the same site but different subdirectory
                        if parsed_href.netloc == parsed_url.netloc and parsed_href.path != parsed_url.path:
                            urls_to_crawl.append(urljoin(current_url, parsed_href.path))
                        else:
                            urls_to_crawl.append(href)
    except requests.exceptions.RequestException as e:
        print(f"An error occurred while crawling: {e}")

# Start crawling from a given URL
starting_url = 'https://forum.infinityfree.net/'
crawl_web(starting_url)
