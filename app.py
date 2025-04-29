print("▶️ 앱 시작 중…")
from flask import Flask, request, make_response, redirect, render_template, url_for, make_response
import json, os

app = Flask(__name__)
DATA_FILE = 'votes.json'
USERS_FILE = 'users.json'

# 1) 아이돌 리스트와 초기 투표수 설정
IDOLS = [
    "ILLIT","KATSEYE","LE SSERAFIM","KiiKii","BLACKPINK",
    "Hearts2Hearts","IVE","aespa","izna","NiziU"
]
INITIAL_COUNTS = {
    "IVE": 152324,
    "aespa": 147621,
    "BLACKPINK": 142576,
    "LE SSERAFIM": 139878,
    "ILLIT": 125993,
    "izna": 117452,
    "Hearts2Hearts": 103264,
    "KiiKii": 98356,
    "NiziU": 81725,
    "KATSEYE": 56847,
}
# vote.json 로드/저장
def load_votes():
    if os.path.exists(DATA_FILE):
        return json.load(open(DATA_FILE,'r', encoding='utf-8'))
    return INITIAL_COUNTS.copy()

def save_votes(votes):
    with open(DATA_FILE, 'w', encoding='utf-8') as f:
        json.dump(votes, f)

#user.json 로드
def load_users():
    if os.path.exists(USERS_FILE):
        return json.load(open(USERS_FILE, 'r', encoding='utf-8'))
    return {}
USERS = load_users()

# 메인 페이지: 인기순위 및 투표 버튼
@app.route('/')
def index():
    votes = load_votes()
    # 내림차순 정렬
    ranking = sorted(votes.items(), key=lambda x: x[1], reverse=True)
    # 이미지 URL 매핑 (static/images/<idol>.jpg 에 이미지를 두세요)
    idols = []
    for name, count in ranking:
        idols.append({
            'name': name,
            'count': f"{count:,}",
            'img_url': url_for('static', filename=f'images/{name}.jpg')
        })
    return render_template('index.html', idols=idols)

# 투표 처리
@app.route('/vote/<idol>', methods=['POST'])
def vote(idol):
    votes = load_votes()
    if idol in votes:
        votes[idol] += 1
        save_votes(votes)
    return redirect(url_for('index'))

# 로그인: GET->폼, POST->검증
@app.route('/login', methods=['GET', 'POST'])
def login():
    error = None
    if request.method == 'POST':
        username = request.form.get('username', '').strip()
        password = request.form.get('password', '')
        if username in USERS and USERS[username] == password:
            role = 'admin' if username == 'admin' else 'guest'
            resp = make_response(redirect(url_for('index')))
            resp.set_cookie('role', role, httponly=True)  
            return resp
        else:
            error = '아이디 또는 비밀번호가 올바르지 않습니다.'
    return render_template('login.html', error=error)

# 관리자 페이지: role 쿠키 확인
@app.route('/admin', methods=['GET','POST'])
def admin():
    if request.cookies.get('role') != 'admin':
        return redirect(url_for('index'))
    votes = load_votes()
    if request.method == 'POST':
        idol = request.form['idol']
        votes[idol] = int(request.form['count'])
        save_votes(votes)
        return redirect(url_for('flag'))
    return render_template('admin.html', idols=IDOLS)

# 플래그 페이지
@app.route('/flag')
def flag():
    votes = load_votes()
    top = max(votes.items(), key=lambda x: x[1])[0]
    if top == 'ILLIT':
        return render_template('flag.html', flag='FLAG{Almond_Chocolate}')
    return redirect(url_for('index'))

if __name__ == '__main__':
    app.run(debug=True)
