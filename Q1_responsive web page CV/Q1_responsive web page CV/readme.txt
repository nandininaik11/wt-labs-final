Design a Responsive CV Website with Video Background using Bootstrap & jQuery
📖 THEORY - DETAILED CONCEPTS
1. What is a Responsive Website?
A responsive website automatically adapts its layout and content to fit different screen sizes - from large desktop monitors (1920px+) to tablets (768px-1024px) to mobile phones (320px-767px). This is achieved using CSS media queries and flexible grid systems. The key principle is 'mobile-first' design, where you design for small screens first, then progressively enhance for larger screens.
Why Responsive Design Matters: Over 60% of web traffic comes from mobile devices. Google prioritizes mobile-friendly sites in search rankings. Users expect seamless experience across devices. A non-responsive site loses visitors and credibility.
2. HTML5 Video Background Implementation
The <video> tag in HTML5 allows embedding videos directly without Flash or plugins. For background videos, we use CSS positioning to layer content over the video. Key attributes:
•	autoplay: Starts video automatically on page load
•	loop: Restarts video from beginning when it ends (infinite loop)
•	muted: Removes audio (required for autoplay in most browsers)
•	playsinline: Prevents fullscreen on mobile iOS devices
Browser support: All modern browsers support MP4 (H.264 codec). For older browsers, provide WebM as fallback. Video should be optimized - compress to reduce file size without quality loss.
3. CSS Positioning & Layering
To place content over video, we use CSS z-index property. Elements are stacked in layers:
•	z-index: -1 → Video layer (bottom-most, behind everything)
•	z-index: 0 → Dark overlay (optional, improves text readability)
•	z-index: 1+ → Content layer (CV sections, text, images)
Position: fixed keeps video in viewport even when scrolling. Transform: translate(-50%, -50%) centers the video perfectly. Overflow: hidden prevents scrollbars.
4. Bootstrap Grid System
Bootstrap uses a 12-column grid system. Each row is divided into 12 equal parts. Classes like col-md-6 mean 'take 6 columns (50%) on medium screens and above'. Breakpoints:
•	xs (extra small): <576px - mobile phones
•	sm (small): ≥576px - large phones
•	md (medium): ≥768px - tablets
•	lg (large): ≥992px - desktops
•	xl (extra large): ≥1200px - large desktops
Example: col-md-4 col-lg-3 means '4 columns on tablets (33%), 3 columns on desktop (25%)'. On mobile (<768px), columns automatically stack vertically (100% width).
5. jQuery for Interactivity
jQuery is a JavaScript library that simplifies DOM manipulation, event handling, and animations. Key features used in this project:
•	$(document).ready() - Waits for DOM to fully load before executing code
•	$(selector).css() - Modifies CSS properties dynamically
•	$(selector).fadeIn/fadeOut() - Creates smooth opacity transitions
•	$(window).scroll() - Detects scroll position for animations
In modern development, many choose vanilla JavaScript over jQuery for performance. However, jQuery remains popular for rapid development and cross-browser compatibility.


⚙️ STEP-BY-STEP SETUP & RUN COMMANDS
Method 1: Direct Opening (Simplest)
1.	Create a new folder on your Desktop called 'My_CV_Project'
2.	Right-click inside the folder → New → Text Document
3.	Rename it from 'New Text Document.txt' to 'cv.html' (make sure .txt is removed)
4.	Right-click cv.html → Open With → Notepad (or any text editor)
5.	Copy the ENTIRE code from above and paste into cv.html
6.	Save the file (Ctrl + S)
7.	Double-click cv.html → It will open in your default browser (Chrome/Firefox/Edge)
Method 2: Using VS Code (Professional)
8.	Open VS Code
9.	File → Open Folder → Select 'My_CV_Project' folder
10.	File → New File → Name it 'cv.html'
11.	Copy-paste the complete code
12.	Save (Ctrl + S)
13.	Right-click on cv.html → Open with Live Server (if Live Server extension installed)
14.	OR: Right-click cv.html in file explorer → Open With → Google Chrome


